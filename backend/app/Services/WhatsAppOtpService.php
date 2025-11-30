<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\SystemSetting;
use App\Services\SecurityLogService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

class WhatsAppOtpService
{
    protected ?string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = $this->getApiKey();
        $this->baseUrl = $this->getBaseUrl();
    }

    /**
     * Get decrypted API key from settings
     */
    protected function getApiKey(): ?string
    {
        $encryptedKey = SystemSetting::get('whatsapp_api_key');

        if (empty($encryptedKey)) {
            return null;
        }

        try {
            return decrypt($encryptedKey);
        } catch (Exception $e) {
            Log::error('Failed to decrypt WhatsApp API key', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Get API base URL from settings
     */
    protected function getBaseUrl(): string
    {
        return SystemSetting::get('whatsapp_api_url', 'https://whatsapp.fimm.app/api');
    }

    /**
     * Check if WhatsApp OTP is enabled
     */
    public function isEnabled(): bool
    {
        return (bool) SystemSetting::get('whatsapp_otp_enabled', false) && !empty($this->apiKey);
    }

    /**
     * Normalize phone number (remove +, spaces, dashes)
     */
    protected function normalizePhone(string $phone): string
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // If starts with 0, replace with 92 (Pakistan)
        if (str_starts_with($phone, '0')) {
            $phone = '92' . substr($phone, 1);
        }

        return $phone;
    }

    /**
     * Make HTTP request to WhatsApp API
     */
    protected function makeRequest(string $method, string $endpoint, array $data = []): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'WhatsApp API key not configured'
            ];
        }

        try {
            $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

            $request = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->timeout(30);

            $response = match (strtoupper($method)) {
                'GET' => $request->get($url, $data),
                'POST' => $request->post($url, $data),
                default => throw new Exception("Unsupported HTTP method: {$method}")
            };

            $responseData = $response->json() ?? [];

            if ($response->successful()) {
                return array_merge(['success' => true], $responseData);
            }

            Log::warning('WhatsApp API request failed', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'response' => $responseData
            ]);

            return [
                'success' => false,
                'message' => $responseData['error'] ?? $responseData['message'] ?? 'API request failed',
                'status_code' => $response->status()
            ];

        } catch (Exception $e) {
            Log::error('WhatsApp API request exception', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if a phone number has WhatsApp
     */
    public function checkNumber(string $phone): array
    {
        $phone = $this->normalizePhone($phone);

        // Cache the result for 24 hours to reduce API calls
        $cacheKey = "whatsapp_check_{$phone}";
        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            return $cached;
        }

        $result = $this->makeRequest('POST', '/otp/check-number', [
            'phone_number' => $phone
        ]);

        // Cache successful results
        if ($result['success']) {
            Cache::put($cacheKey, $result, now()->addHours(24));
        }

        return $result;
    }

    /**
     * Send OTP to phone number via WhatsApp
     */
    public function sendOtp(string $phone, string $purpose = 'login', ?string $ipAddress = null, ?string $userAgent = null): array
    {
        try {
            $normalizedPhone = $this->normalizePhone($phone);

            // Check rate limiting (reuse Firebase pattern)
            $rateLimitCheck = $this->checkRateLimit($phone);
            if (!$rateLimitCheck['allowed']) {
                return [
                    'success' => false,
                    'message' => $rateLimitCheck['message']
                ];
            }

            // Send OTP via WhatsApp API
            $response = $this->makeRequest('POST', '/otp/send', [
                'phone_number' => $normalizedPhone
            ]);

            if (!$response['success']) {
                return $response;
            }

            $referenceId = $response['reference_id'] ?? null;
            $expiresIn = $response['expires_in_seconds'] ?? 300;
            $expiryMinutes = ceil($expiresIn / 60);

            // Save OTP record to database
            OtpVerification::create([
                'phone' => $phone, // Store original format
                'otp_code' => 'WHATSAPP', // Placeholder - actual code is with WhatsApp
                'purpose' => $purpose,
                'channel' => 'whatsapp',
                'reference_id' => $referenceId,
                'expires_at' => Carbon::now()->addSeconds($expiresIn),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            Log::info('WhatsApp OTP sent', [
                'phone' => $this->maskPhone($phone),
                'purpose' => $purpose,
                'reference_id' => $referenceId
            ]);

            return [
                'success' => true,
                'message' => 'OTP sent to your WhatsApp',
                'expires_in_minutes' => $expiryMinutes,
                'reference_id' => $referenceId,
                'channel' => 'whatsapp',
                'is_ufone_bypass' => false,
                'otp_code' => null,
            ];

        } catch (Exception $e) {
            Log::error('Failed to send WhatsApp OTP', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ];
        }
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $phone, string $otp, string $purpose = 'login'): array
    {
        try {
            $normalizedPhone = $this->normalizePhone($phone);

            // Find the latest unverified WhatsApp OTP for this phone
            $otpRecord = OtpVerification::where('phone', $phone)
                ->where('purpose', $purpose)
                ->where('channel', 'whatsapp')
                ->where('is_verified', false)
                ->where('expires_at', '>', Carbon::now())
                ->latest()
                ->first();

            if (!$otpRecord) {
                return [
                    'success' => false,
                    'message' => 'No pending OTP found. Please request a new one.'
                ];
            }

            // Verify with WhatsApp API
            $response = $this->makeRequest('POST', '/otp/verify', [
                'phone_number' => $normalizedPhone,
                'otp' => $otp
            ]);

            if (!$response['success']) {
                // Increment attempts
                $otpRecord->incrementAttempts();

                if ($otpRecord->attempts >= config('firebase.otp.max_attempts', 3)) {
                    SecurityLogService::logSuspiciousActivity('whatsapp_otp_excessive_attempts', [
                        'phone' => $phone,
                        'purpose' => $purpose,
                        'attempts' => $otpRecord->attempts
                    ]);
                }

                return [
                    'success' => false,
                    'message' => $response['message'] ?? 'Invalid OTP code'
                ];
            }

            // Check if verified
            if (!($response['verified'] ?? false)) {
                $otpRecord->incrementAttempts();
                return [
                    'success' => false,
                    'message' => 'Invalid OTP code. Please check and try again.'
                ];
            }

            // Mark as verified
            $otpRecord->markAsVerified();

            return [
                'success' => true,
                'message' => 'OTP verified successfully',
                'otp_id' => $otpRecord->id
            ];

        } catch (Exception $e) {
            Log::error('Failed to verify WhatsApp OTP', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Verification failed. Please try again.'
            ];
        }
    }

    /**
     * Get OTP status by reference ID
     */
    public function getStatus(string $referenceId): array
    {
        return $this->makeRequest('GET', "/otp/status/{$referenceId}");
    }

    /**
     * Test API connection
     */
    public function testConnection(): array
    {
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'API key not configured'
            ];
        }

        try {
            // Use check-number endpoint with a test number
            $response = Http::withHeaders([
                'X-API-Key' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(10)->post(rtrim($this->baseUrl, '/') . '/otp/check-number', [
                'phone_number' => '923000000000'
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => 'Connection successful'
                ];
            }

            $data = $response->json();

            // 401/403 means API key is invalid
            if (in_array($response->status(), [401, 403])) {
                return [
                    'success' => false,
                    'message' => 'Invalid API key'
                ];
            }

            return [
                'success' => false,
                'message' => $data['error'] ?? $data['message'] ?? 'Connection failed'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check rate limiting for OTP requests
     */
    protected function checkRateLimit(string $phone): array
    {
        // Check resend delay - only for recent unverified OTPs (last 10 minutes)
        $lastUnverifiedOtp = OtpVerification::where('phone', $phone)
            ->where('channel', 'whatsapp')
            ->where('is_verified', false)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->latest()
            ->first();

        if ($lastUnverifiedOtp) {
            $resendDelaySeconds = config('firebase.otp.resend_delay_seconds', 60);
            $secondsSinceLastOtp = (int) abs(Carbon::now()->diffInSeconds($lastUnverifiedOtp->created_at));

            if ($secondsSinceLastOtp < $resendDelaySeconds) {
                $waitSeconds = $resendDelaySeconds - $secondsSinceLastOtp;
                return [
                    'allowed' => false,
                    'message' => "Please wait {$waitSeconds} seconds before requesting another OTP."
                ];
            }
        }

        // Check hourly limit
        $recentUnverifiedOtpCount = OtpVerification::where('phone', $phone)
            ->where('channel', 'whatsapp')
            ->where('is_verified', false)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        $hourlyLimit = 5;

        if ($recentUnverifiedOtpCount >= $hourlyLimit) {
            SecurityLogService::logExcessiveOtpRequests($phone, $recentUnverifiedOtpCount);

            return [
                'allowed' => false,
                'message' => 'Too many OTP requests. Please try again later.'
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Mask phone number for display
     */
    protected function maskPhone(string $phone): string
    {
        if (strlen($phone) < 7) {
            return $phone;
        }

        return substr($phone, 0, 5) . '****' . substr($phone, -2);
    }
}
