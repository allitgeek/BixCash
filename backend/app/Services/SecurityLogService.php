<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SecurityLogService
{
    /**
     * Log suspicious authentication activity
     */
    public static function logSuspiciousActivity(string $type, array $data): void
    {
        $logData = [
            'type' => $type,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => now()->toDateTimeString(),
            'data' => $data
        ];

        Log::channel('security')->warning("Suspicious Activity: {$type}", $logData);

        // Also log to database for analysis
        try {
            DB::table('security_logs')->insert([
                'type' => $type,
                'ip_address' => $logData['ip_address'],
                'user_agent' => $logData['user_agent'],
                'data' => json_encode($data),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Silently fail if table doesn't exist
            Log::error('Failed to log security event to database', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Log failed login attempts
     */
    public static function logFailedLogin(string $phone, string $reason): void
    {
        self::logSuspiciousActivity('failed_login', [
            'phone' => $phone,
            'reason' => $reason
        ]);
    }

    /**
     * Log excessive OTP requests
     */
    public static function logExcessiveOtpRequests(string $phone, int $count): void
    {
        self::logSuspiciousActivity('excessive_otp_requests', [
            'phone' => $phone,
            'request_count' => $count
        ]);
    }

    /**
     * Log PIN lockout events
     */
    public static function logPinLockout(string $phone, int $attempts): void
    {
        self::logSuspiciousActivity('pin_lockout', [
            'phone' => $phone,
            'failed_attempts' => $attempts
        ]);
    }

    /**
     * Log invalid OTP attempts
     */
    public static function logInvalidOtp(string $phone, int $attempts): void
    {
        self::logSuspiciousActivity('invalid_otp', [
            'phone' => $phone,
            'attempts' => $attempts
        ]);
    }

    /**
     * Check if IP is blocked
     */
    public static function isIpBlocked(string $ip): bool
    {
        try {
            $count = DB::table('security_logs')
                ->where('ip_address', $ip)
                ->where('created_at', '>=', now()->subHour())
                ->count();

            // Block if more than 50 suspicious activities in 1 hour
            return $count > 50;
        } catch (\Exception $e) {
            return false;
        }
    }
}
