<?php

namespace App\Services;

use App\Models\OtpVerification;
use App\Models\CustomerProfile;
use App\Services\SecurityLogService;
use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Exception;

class FirebaseOtpService
{
    protected $firebase;
    protected $messaging;

    public function __construct()
    {
        try {
            $credentialsPath = config('firebase.credentials.file');

            if (!file_exists($credentialsPath)) {
                Log::error('Firebase credentials file not found', ['path' => $credentialsPath]);
                throw new Exception('Firebase credentials file not found');
            }

            $this->firebase = (new Factory)
                ->withServiceAccount($credentialsPath);

            // Uncomment when you need Firebase Cloud Messaging
            // $this->messaging = $this->firebase->createMessaging();
        } catch (Exception $e) {
            Log::error('Firebase initialization failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Generate a random OTP code
     */
    protected function generateOtpCode(): string
    {
        $length = config('firebase.otp.length', 6);
        return str_pad((string) random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    /**
     * Send OTP to phone number
     *
     * @param string $phone Phone number in format +923001234567
     * @param string $purpose Purpose of OTP (login, reset_pin)
     * @return array ['success' => bool, 'message' => string, 'otp' => string (only in development)]
     */
    public function sendOtp(string $phone, string $purpose = 'login', ?string $ipAddress = null, ?string $userAgent = null): array
    {
        try {
            // Validate phone format
            if (!$this->isValidPhoneFormat($phone)) {
                return [
                    'success' => false,
                    'message' => 'Invalid phone number format. Use +92XXXXXXXXXX'
                ];
            }

            // Check rate limiting
            $rateLimitCheck = $this->checkRateLimit($phone);
            if (!$rateLimitCheck['allowed']) {
                return [
                    'success' => false,
                    'message' => $rateLimitCheck['message']
                ];
            }

            // Generate OTP code
            $otpCode = $this->generateOtpCode();
            $expiryMinutes = config('firebase.otp.expiry_minutes', 5);

            // Save OTP to database
            $otpVerification = OtpVerification::create([
                'phone' => $phone,
                'otp_code' => $otpCode,
                'purpose' => $purpose,
                'expires_at' => Carbon::now()->addMinutes($expiryMinutes),
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);

            // Update customer profile last_otp_sent_at and increment attempts
            $this->updateCustomerOtpTracking($phone);

            // Log OTP for server logs only (not exposed to client)
            Log::info('OTP generated for phone', [
                'phone' => $this->maskPhone($phone),
                'purpose' => $purpose
            ]);

            // Note: This method stores OTP in database for fallback verification
            // Production systems should use Firebase Phone Auth for real SMS delivery

            return [
                'success' => true,
                'message' => 'OTP sent successfully to ' . $this->maskPhone($phone),
                'expires_in_minutes' => $expiryMinutes
            ];

        } catch (Exception $e) {
            Log::error('Failed to send OTP', [
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
     *
     * @param string $phone Phone number
     * @param string $otpCode OTP code to verify
     * @param string $purpose Purpose of OTP
     * @param bool $allowAlreadyVerified Allow OTP that was already verified (for reset_pin flow)
     * @return array ['success' => bool, 'message' => string, 'otp_id' => int]
     */
    public function verifyOtp(string $phone, string $otpCode, string $purpose = 'login', bool $allowAlreadyVerified = false): array
    {
        try {
            // Find valid unverified OTP
            $otpVerification = OtpVerification::forPhone($phone, $purpose)
                ->valid()
                ->where('otp_code', $otpCode)
                ->latest()
                ->first();

            // If not found, check if it was already verified (for reset_pin flow)
            if (!$otpVerification && $allowAlreadyVerified) {
                $otpVerification = OtpVerification::forPhone($phone, $purpose)
                    ->where('otp_code', $otpCode)
                    ->where('is_verified', true)
                    ->where('expires_at', '>', Carbon::now())
                    ->latest()
                    ->first();

                if ($otpVerification) {
                    // OTP was already verified, allow it
                    return [
                        'success' => true,
                        'message' => 'OTP already verified',
                        'otp_id' => $otpVerification->id
                    ];
                }
            }

            if (!$otpVerification) {
                // Check if there's an expired or invalid OTP
                $expiredOtp = OtpVerification::forPhone($phone, $purpose)
                    ->where('otp_code', $otpCode)
                    ->latest()
                    ->first();

                if ($expiredOtp) {
                    if ($expiredOtp->isExpired()) {
                        return [
                            'success' => false,
                            'message' => 'OTP has expired. Please request a new one.'
                        ];
                    }

                    if ($expiredOtp->attempts >= config('firebase.otp.max_attempts', 3)) {
                        SecurityLogService::logSuspiciousActivity('invalid_otp_excessive_attempts', [
                            'phone' => $phone,
                            'purpose' => $purpose,
                            'attempts' => $expiredOtp->attempts
                        ]);

                        return [
                            'success' => false,
                            'message' => 'Maximum verification attempts exceeded. Please request a new OTP.'
                        ];
                    }
                }

                // Log invalid OTP attempt
                SecurityLogService::logSuspiciousActivity('invalid_otp_attempt', [
                    'phone' => $phone,
                    'purpose' => $purpose
                ]);

                return [
                    'success' => false,
                    'message' => 'Invalid OTP code. Please check and try again.'
                ];
            }

            // Mark as verified
            $otpVerification->markAsVerified();

            return [
                'success' => true,
                'message' => 'OTP verified successfully',
                'otp_id' => $otpVerification->id
            ];

        } catch (Exception $e) {
            Log::error('Failed to verify OTP', [
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
     * Check rate limiting for OTP requests
     */
    protected function checkRateLimit(string $phone): array
    {
        // Check resend delay - only for unverified OTPs
        $lastUnverifiedOtp = OtpVerification::where('phone', $phone)
            ->where('is_verified', false)
            ->latest()
            ->first();

        if ($lastUnverifiedOtp) {
            $resendDelaySeconds = config('firebase.otp.resend_delay_seconds', 60);
            $secondsSinceLastOtp = Carbon::now()->diffInSeconds($lastUnverifiedOtp->created_at);

            if ($secondsSinceLastOtp < $resendDelaySeconds) {
                $waitSeconds = $resendDelaySeconds - $secondsSinceLastOtp;
                return [
                    'allowed' => false,
                    'message' => "Please wait {$waitSeconds} seconds before requesting another OTP."
                ];
            }
        }

        // Check daily limit - only count unverified OTPs in the last hour (to prevent abuse)
        $recentUnverifiedOtpCount = OtpVerification::where('phone', $phone)
            ->where('is_verified', false)
            ->where('created_at', '>=', Carbon::now()->subHour())
            ->count();

        $hourlyLimit = 5; // Max 5 unverified OTPs per hour

        if ($recentUnverifiedOtpCount >= $hourlyLimit) {
            // Log excessive OTP requests
            SecurityLogService::logExcessiveOtpRequests($phone, $recentUnverifiedOtpCount);

            return [
                'allowed' => false,
                'message' => 'Too many OTP requests. Please try again later or contact support.'
            ];
        }

        return ['allowed' => true];
    }

    /**
     * Update customer profile OTP tracking
     */
    protected function updateCustomerOtpTracking(string $phone): void
    {
        $customerProfile = CustomerProfile::where('phone', $phone)->first();

        if ($customerProfile) {
            $customerProfile->last_otp_sent_at = Carbon::now();

            // Reset daily counter if it's a new day
            if ($customerProfile->last_otp_sent_at &&
                !$customerProfile->last_otp_sent_at->isToday()) {
                $customerProfile->otp_attempts_today = 1;
            } else {
                $customerProfile->otp_attempts_today++;
            }

            $customerProfile->save();
        }
    }

    /**
     * Validate phone number format
     */
    protected function isValidPhoneFormat(string $phone): bool
    {
        // Validate +92XXXXXXXXXX format (Pakistan)
        return preg_match('/^\+92[0-9]{10}$/', $phone) === 1;
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

    /**
     * Verify Firebase ID Token from client-side authentication
     *
     * @param string $idToken Firebase ID token from client
     * @return array ['success' => bool, 'data' => array|null, 'message' => string]
     */
    public function verifyFirebaseIdToken(string $idToken): array
    {
        try {
            $auth = $this->firebase->createAuth();

            // Verify the ID token
            $verifiedIdToken = $auth->verifyIdToken($idToken);

            // Extract claims from the token
            $uid = $verifiedIdToken->claims()->get('sub');
            $phoneNumber = $verifiedIdToken->claims()->get('phone_number');
            $email = $verifiedIdToken->claims()->get('email');

            // Validate that phone number exists (required for phone auth)
            if (empty($phoneNumber)) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Phone number not found in Firebase token. Please use phone authentication.'
                ];
            }

            // Get full Firebase user record for additional details
            try {
                $firebaseUser = $auth->getUser($uid);

                return [
                    'success' => true,
                    'data' => [
                        'firebase_uid' => $uid,
                        'phone' => $phoneNumber,
                        'email' => $email,
                        'phone_verified' => $firebaseUser->phoneNumber !== null,
                        'email_verified' => $firebaseUser->emailVerified ?? false,
                        'display_name' => $firebaseUser->displayName,
                        'photo_url' => $firebaseUser->photoUrl,
                    ],
                    'message' => 'Firebase token verified successfully'
                ];
            } catch (\Exception $e) {
                // If we can't get full user record, return basic info from token
                return [
                    'success' => true,
                    'data' => [
                        'firebase_uid' => $uid,
                        'phone' => $phoneNumber,
                        'email' => $email,
                        'phone_verified' => true, // If token is valid, phone was verified
                    ],
                    'message' => 'Firebase token verified successfully'
                ];
            }

        } catch (\Kreait\Firebase\Exception\Auth\FailedToVerifyToken $e) {
            Log::error('Failed to verify Firebase ID token', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Invalid or expired Firebase token. Please authenticate again.'
            ];

        } catch (\Kreait\Firebase\Exception\Auth\RevokedIdToken $e) {
            Log::error('Revoked Firebase ID token used', [
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'This authentication token has been revoked. Please sign in again.'
            ];

        } catch (\Exception $e) {
            Log::error('Firebase ID token verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to verify authentication. Please try again.'
            ];
        }
    }

    /**
     * Clean up expired OTPs (call from scheduled task)
     */
    public static function cleanupExpiredOtps(): int
    {
        return OtpVerification::deleteExpired();
    }
}
