<?php

namespace App\Services;

use App\Models\User;
use App\Models\WithdrawalRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FraudDetectionService
{
    /**
     * Analyze a withdrawal request for fraud indicators
     * Returns [fraud_score, fraud_flags]
     */
    public static function analyze(User $user, float $amount, $request = null): array
    {
        $fraudScore = 0;
        $fraudFlags = [];

        // 1. Check for rapid withdrawals (>3 in 24 hours)
        $recentWithdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->where('created_at', '>=', Carbon::now()->subHours(24))
            ->count();

        if ($recentWithdrawals >= 3) {
            $fraudScore += 30;
            $fraudFlags[] = 'rapid_withdrawals';
        }

        // 2. Check for new account (<7 days old)
        $accountAge = Carbon::parse($user->created_at)->diffInDays(Carbon::now());
        if ($accountAge < 7) {
            $fraudScore += 25;
            $fraudFlags[] = 'new_account';
        }

        // 3. Check for large first withdrawal (>Rs. 10,000)
        $previousWithdrawals = WithdrawalRequest::where('user_id', $user->id)->count();
        if ($previousWithdrawals == 0 && $amount > 10000) {
            $fraudScore += 20;
            $fraudFlags[] = 'large_first_withdrawal';
        }

        // 4. Check if bank details were recently changed
        $profile = $user->customerProfile;
        if ($profile && $profile->bank_details_last_updated) {
            $hoursSinceChange = Carbon::parse($profile->bank_details_last_updated)->diffInHours(Carbon::now());
            if ($hoursSinceChange < 48) {
                $fraudScore += 15;
                $fraudFlags[] = 'recent_bank_change';
            }
        }

        // 5. Check if withdrawal equals exact wallet balance
        $wallet = $user->wallet;
        if ($wallet && abs($wallet->balance - $amount) < 0.01) {
            $fraudScore += 10;
            $fraudFlags[] = 'exact_balance_withdrawal';
        }

        // 6. Check for IP address change (if request object provided)
        if ($request && $user->last_login_ip) {
            if ($request->ip() !== $user->last_login_ip) {
                $fraudScore += 15;
                $fraudFlags[] = 'ip_address_change';
            }
        }

        // 7. Check for unusually large withdrawal relative to history
        if ($previousWithdrawals > 0) {
            $avgWithdrawal = WithdrawalRequest::where('user_id', $user->id)
                ->where('status', 'completed')
                ->avg('amount');

            if ($avgWithdrawal && $amount > ($avgWithdrawal * 3)) {
                $fraudScore += 15;
                $fraudFlags[] = 'unusually_large_amount';
            }
        }

        // Cap fraud score at 100
        $fraudScore = min($fraudScore, 100);

        return [$fraudScore, $fraudFlags];
    }

    /**
     * Determine if a withdrawal should be automatically flagged for manual review
     */
    public static function requiresManualReview(int $fraudScore, array $fraudFlags): bool
    {
        // Flag for manual review if fraud score is high or critical flags present
        if ($fraudScore >= 50) {
            return true;
        }

        $criticalFlags = ['rapid_withdrawals', 'recent_bank_change', 'ip_address_change'];
        $hasCriticalFlag = !empty(array_intersect($fraudFlags, $criticalFlags));

        return $hasCriticalFlag;
    }
}
