<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class OtpManagerService
{
    protected FirebaseOtpService $firebaseService;
    protected WhatsAppOtpService $whatsappService;

    public function __construct(FirebaseOtpService $firebaseService, WhatsAppOtpService $whatsappService)
    {
        $this->firebaseService = $firebaseService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * Get the primary OTP provider setting
     */
    public function getPrimaryProvider(): string
    {
        return SystemSetting::get('primary_otp_provider', 'firebase');
    }

    /**
     * Check if login 2FA is enabled
     */
    public function isLogin2FAEnabled(): bool
    {
        return (bool) SystemSetting::get('login_2fa_enabled', false);
    }

    /**
     * Check if transaction OTP is enabled
     */
    public function isTransactionOtpEnabled(): bool
    {
        return (bool) SystemSetting::get('transaction_otp_enabled', false);
    }

    /**
     * Get transaction OTP threshold
     */
    public function getTransactionOtpThreshold(): float
    {
        return (float) SystemSetting::get('transaction_otp_threshold', 0);
    }

    /**
     * Check if transaction requires OTP verification
     */
    public function transactionRequiresOtp(float $amount): bool
    {
        if (!$this->isTransactionOtpEnabled()) {
            return false;
        }

        $threshold = $this->getTransactionOtpThreshold();
        return $threshold > 0 && $amount >= $threshold;
    }

    /**
     * Send OTP using cascading fallback system:
     * 1. WhatsApp (if enabled + number has WhatsApp)
     * 2. Firebase (handles Ufone bypass internally)
     */
    public function sendOtp(string $phone, string $purpose = 'login', ?string $ipAddress = null, ?string $userAgent = null): array
    {
        // Step 1: Try WhatsApp first (if enabled)
        if ($this->whatsappService->isEnabled()) {
            try {
                // Check if the number has WhatsApp
                $hasWhatsApp = $this->checkWhatsAppNumber($phone);

                if ($hasWhatsApp) {
                    Log::info('Attempting WhatsApp OTP', ['phone' => $this->maskPhone($phone)]);

                    $result = $this->whatsappService->sendOtp($phone, $purpose, $ipAddress, $userAgent);

                    Log::info('WhatsApp OTP result', [
                        'phone' => $this->maskPhone($phone),
                        'success' => $result['success'] ?? 'not set',
                        'channel' => $result['channel'] ?? 'not set',
                        'is_ufone_bypass' => $result['is_ufone_bypass'] ?? 'not set',
                        'message' => $result['message'] ?? 'not set'
                    ]);

                    if ($result['success']) {
                        Log::info('WhatsApp OTP sent successfully - RETURNING', ['phone' => $this->maskPhone($phone)]);
                        return $result;
                    }

                    // Log failure and fall back to Firebase
                    Log::warning('WhatsApp OTP failed, falling back to Firebase', [
                        'phone' => $this->maskPhone($phone),
                        'error' => $result['message'] ?? 'Unknown error'
                    ]);
                } else {
                    Log::info('Number does not have WhatsApp, using Firebase', ['phone' => $this->maskPhone($phone)]);
                }
            } catch (Exception $e) {
                Log::error('WhatsApp OTP exception, falling back to Firebase', [
                    'phone' => $this->maskPhone($phone),
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Step 2: Firebase fallback (handles Ufone bypass internally)
        Log::info('Falling through to Firebase OTP', ['phone' => $this->maskPhone($phone)]);
        $firebaseResult = $this->firebaseService->sendOtp($phone, $purpose, $ipAddress, $userAgent);
        Log::info('Firebase OTP result', [
            'phone' => $this->maskPhone($phone),
            'success' => $firebaseResult['success'] ?? 'not set',
            'channel' => $firebaseResult['channel'] ?? 'not set',
            'is_ufone_bypass' => $firebaseResult['is_ufone_bypass'] ?? 'not set',
        ]);
        return $firebaseResult;
    }

    /**
     * Mask phone number for logging
     */
    protected function maskPhone(string $phone): string
    {
        if (strlen($phone) < 7) {
            return $phone;
        }
        return substr($phone, 0, 5) . '****' . substr($phone, -2);
    }

    /**
     * Verify OTP - automatically detects the channel from the database
     */
    public function verifyOtp(string $phone, string $otp, string $purpose = 'login', bool $allowAlreadyVerified = false): array
    {
        // Find the latest unverified OTP for this phone and purpose
        $otpRecord = OtpVerification::where('phone', $phone)
            ->where('purpose', $purpose)
            ->where('is_verified', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            // If not found and allowAlreadyVerified, check for already verified OTP
            if ($allowAlreadyVerified) {
                $otpRecord = OtpVerification::where('phone', $phone)
                    ->where('purpose', $purpose)
                    ->where('is_verified', true)
                    ->where('expires_at', '>', Carbon::now())
                    ->latest()
                    ->first();

                if ($otpRecord) {
                    return [
                        'success' => true,
                        'message' => 'OTP already verified',
                        'otp_id' => $otpRecord->id
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'No pending OTP found. Please request a new one.'
            ];
        }

        // Determine which service to use based on channel
        $channel = $otpRecord->channel ?? 'firebase';

        if ($channel === 'whatsapp') {
            return $this->whatsappService->verifyOtp($phone, $otp, $purpose);
        }

        // Default to Firebase
        return $this->firebaseService->verifyOtp($phone, $otp, $purpose, $allowAlreadyVerified);
    }

    /**
     * Check if a phone number has WhatsApp
     */
    public function checkWhatsAppNumber(string $phone): bool
    {
        if (!$this->whatsappService->isEnabled()) {
            return false;
        }

        try {
            $result = $this->whatsappService->checkNumber($phone);
            return $result['success'] && ($result['has_whatsapp'] ?? false);
        } catch (Exception $e) {
            Log::error('Failed to check WhatsApp number', [
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get the Firebase service directly (for backward compatibility)
     */
    public function getFirebaseService(): FirebaseOtpService
    {
        return $this->firebaseService;
    }

    /**
     * Get the WhatsApp service directly
     */
    public function getWhatsAppService(): WhatsAppOtpService
    {
        return $this->whatsappService;
    }

    /**
     * Verify Firebase ID token (delegates to Firebase service)
     */
    public function verifyFirebaseIdToken(string $idToken): array
    {
        return $this->firebaseService->verifyFirebaseIdToken($idToken);
    }
}
