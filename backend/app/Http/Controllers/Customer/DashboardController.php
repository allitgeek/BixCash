<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankDetailsRequest;
use App\Models\BankDetailsHistory;
use App\Models\Brand;
use App\Models\CustomerProfile;
use App\Models\PartnerTransaction;
use App\Models\Promotion;
use App\Models\PurchaseHistory;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use App\Services\OtpManagerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // No need for constructor middleware - routes already have 'auth' middleware applied

    public function index()
    {
        $user = Auth::user();

        // Get or create wallet (cached for 5 minutes)
        $wallet = Cache::remember("user.{$user->id}.wallet", 300, function () use ($user) {
            return Wallet::firstOrCreate(
                ['user_id' => $user->id],
                ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
            );
        });

        // Get profile (cached for 5 minutes)
        $profile = Cache::remember("user.{$user->id}.profile", 300, function () use ($user) {
            return CustomerProfile::where('user_id', $user->id)->first();
        });

        // Get recent purchases
        $recentPurchases = PurchaseHistory::where('user_id', $user->id)
            ->with('brand')
            ->latest()
            ->take(5)
            ->get();

        // Get recent withdrawals
        $recentWithdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Get pending partner transactions (for confirmation)
        $pendingTransactions = PartnerTransaction::where('customer_id', $user->id)
            ->where('status', 'pending_confirmation')
            ->with('partner.partnerProfile')
            ->latest()
            ->get();

        // Get active brands (cached for 15 minutes) - for server-side rendering
        $brands = Cache::remember('dashboard_brands', 900, function () {
            return Brand::where('is_active', true)
                ->with('category:id,name')
                ->select(['id', 'name', 'logo_path', 'category_id', 'order'])
                ->orderBy('order', 'asc')
                ->get();
        });

        // Get active promotions (cached for 15 minutes) - for server-side rendering
        $promotions = Cache::remember('dashboard_promotions', 900, function () {
            return Promotion::active()
                ->ordered()
                ->get();
        });

        // Check if profile is complete
        $profileComplete = $profile && $profile->profile_completed;

        return view('customer.dashboard', compact('user', 'wallet', 'profile', 'recentPurchases', 'recentWithdrawals', 'pendingTransactions', 'profileComplete', 'brands', 'promotions'));
    }

    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
        ]);

        $user = Auth::user();

        // Update user name and email
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? $user->email,
        ]);

        // Update or create profile and mark as completed
        $profile = CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'profile_completed' => 1, // Explicitly set to 1 for database compatibility
            ]
        );

        // Ensure it's saved
        $profile->profile_completed = 1;
        $profile->save();

        return redirect()->route('customer.dashboard')->with('success', 'Profile completed successfully!');
    }

    public function profile()
    {
        $user = Auth::user();
        $profile = CustomerProfile::where('user_id', $user->id)->first();

        // Check if OTP modal session has expired
        if (session('show_otp_modal') && session('otp_modal_expires_at')) {
            $expiresAt = session('otp_modal_expires_at');
            if (now()->timestamp > $expiresAt) {
                // Session expired, clear it
                session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at', 'otp_channel', 'is_ufone_bypass', 'ufone_otp_code']);
                Log::info('OTP modal session expired and cleared', ['user_id' => $user->id]);
            }
        }

        return view('customer.profile', compact('user', 'profile'));
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before_or_equal:today',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Update user
        $user->name = $validated['name'];
        if (isset($validated['email'])) {
            $user->email = $validated['email'];
        }
        $user->save();

        // Get or create profile
        $profile = CustomerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['profile_completed' => 0]
        );

        // Update only fields that are present in request AND have non-empty values
        if (! empty($validated['phone'])) {
            $profile->phone = $validated['phone'];
        }
        if (! empty($validated['date_of_birth'])) {
            $profile->date_of_birth = $validated['date_of_birth'];
        }
        if (! empty($validated['address'])) {
            $profile->address = $validated['address'];
        }
        if (! empty($validated['city'])) {
            $profile->city = $validated['city'];
        }
        if (! empty($validated['state'])) {
            $profile->state = $validated['state'];
        }
        if (! empty($validated['postal_code'])) {
            $profile->postal_code = $validated['postal_code'];
        }

        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function requestBankDetailsOtp(BankDetailsRequest $request)
    {
        // Validation is handled by BankDetailsRequest
        $validated = $request->validated();
        $user = Auth::user();

        // Send OTP via OtpManagerService (cascading: WhatsApp → Firebase → Ufone)
        $otpService = app(OtpManagerService::class);
        $otpResult = $otpService->sendOtp(
            $user->phone,
            'bank_details',
            $request->ip(),
            $request->userAgent()
        );

        if (!$otpResult['success']) {
            Log::warning('Bank details OTP send failed', [
                'user_id' => $user->id,
                'error' => $otpResult['message'] ?? 'Unknown error',
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $otpResult['message'] ?? 'Failed to send OTP',
                ], 400);
            }

            return redirect()->back()->with('error', $otpResult['message'] ?? 'Failed to send OTP');
        }

        // Store pending bank details in session
        session([
            'pending_bank_data' => $validated,
            'show_otp_modal' => true,
            'otp_modal_expires_at' => now()->addMinutes(10)->timestamp,
            'otp_channel' => $otpResult['channel'] ?? 'firebase',
            'is_ufone_bypass' => $otpResult['is_ufone_bypass'] ?? false,
            'ufone_otp_code' => $otpResult['otp_code'] ?? null,
        ]);

        Log::info('Bank details OTP requested', [
            'user_id' => $user->id,
            'bank_name' => $validated['bank_name'],
            'channel' => $otpResult['channel'] ?? 'firebase',
            'ip' => $request->ip(),
        ]);

        // Return JSON for AJAX requests (TPIN flow)
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $otpResult['message'] ?? 'OTP sent successfully',
                'channel' => $otpResult['channel'] ?? 'firebase',
                'is_ufone_bypass' => $otpResult['is_ufone_bypass'] ?? false,
                'otp_code' => $otpResult['otp_code'] ?? null,
            ]);
        }

        return redirect()->back();
    }

    public function verifyBankDetailsOtp(Request $request)
    {
        $user = Auth::user();

        // Validate OTP code
        $request->validate([
            'verification_code' => 'required|string|min:6|max:6',
        ]);

        // Get pending bank data from session
        $pendingBankData = session('pending_bank_data');
        if (!$pendingBankData) {
            Log::warning('Bank details verification failed - session expired', ['user_id' => $user->id]);

            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again.',
            ], 400);
        }

        // Verify OTP via OtpManagerService
        $otpService = app(OtpManagerService::class);
        $verifyResult = $otpService->verifyOtp(
            $user->phone,
            $request->verification_code,
            'bank_details'
        );

        if (!$verifyResult['success']) {
            Log::warning('Bank details OTP verification failed', [
                'user_id' => $user->id,
                'error' => $verifyResult['message'] ?? 'Invalid OTP',
            ]);

            return response()->json([
                'success' => false,
                'message' => $verifyResult['message'] ?? 'Invalid verification code',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Get or create profile
            $profile = CustomerProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['profile_completed' => 0]
            );

            // Store old values for audit log
            $oldValues = [
                'bank_name' => $profile->bank_name,
                'account_number' => $profile->account_number,
                'account_title' => $profile->account_title,
                'iban' => $profile->iban,
            ];

            // Determine if this is a create or update
            $action = $profile->bank_name ? 'updated' : 'created';

            // Update bank details
            $profile->bank_name = $pendingBankData['bank_name'];
            $profile->account_number = $pendingBankData['account_number'];
            $profile->account_title = $pendingBankData['account_title'];
            $profile->iban = $pendingBankData['iban'];
            $profile->bank_details_last_updated = now();
            $profile->withdrawal_locked_until = now()->addHours(24);
            $profile->save();

            // Log change to audit trail
            BankDetailsHistory::logChange(
                userId: $user->id,
                action: $action,
                oldValues: $action === 'updated' ? $oldValues : null,
                newValues: $pendingBankData,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent()
            );

            DB::commit();

            // Clear session
            session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at', 'otp_channel', 'is_ufone_bypass', 'ufone_otp_code']);

            Log::info('Bank details updated successfully', [
                'user_id' => $user->id,
                'action' => $action,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully! Withdrawals will be locked for 24 hours for security.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bank details update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating bank details. Please try again.',
            ], 500);
        }
    }

    public function resendBankDetailsOtp(Request $request)
    {
        $user = Auth::user();

        // Check if there's pending bank data in session
        $pendingBankData = session('pending_bank_data');
        if (!$pendingBankData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please start over.',
            ], 400);
        }

        // Resend OTP via OtpManagerService
        $otpService = app(OtpManagerService::class);
        $otpResult = $otpService->sendOtp(
            $user->phone,
            'bank_details',
            $request->ip(),
            $request->userAgent()
        );

        if (!$otpResult['success']) {
            Log::warning('Bank details OTP resend failed', [
                'user_id' => $user->id,
                'error' => $otpResult['message'] ?? 'Unknown error',
            ]);

            return response()->json([
                'success' => false,
                'message' => $otpResult['message'] ?? 'Failed to resend OTP',
            ], 400);
        }

        // Refresh session expiry
        session(['otp_modal_expires_at' => now()->addMinutes(10)->timestamp]);

        Log::info('Bank details OTP resent', [
            'user_id' => $user->id,
            'channel' => $otpResult['channel'] ?? 'firebase',
            'ip' => $request->ip(),
        ]);

        return response()->json([
            'success' => true,
            'message' => $otpResult['message'] ?? 'OTP resent successfully',
            'channel' => $otpResult['channel'] ?? 'firebase',
            'is_ufone_bypass' => $otpResult['is_ufone_bypass'] ?? false,
            'otp_code' => $otpResult['otp_code'] ?? null,
        ]);
    }

    public function verifyBankDetailsTpin(Request $request)
    {
        // Validate TPIN input
        $validated = $request->validate([
            'pin' => 'required|string|size:4|regex:/^[0-9]{4}$/',
        ], [
            'pin.required' => 'TPIN is required.',
            'pin.size' => 'TPIN must be exactly 4 digits.',
            'pin.regex' => 'TPIN must contain only numbers.',
        ]);

        $user = Auth::user();

        // Get pending bank data from session
        $pendingBankData = session('pending_bank_data');
        if (! $pendingBankData) {
            Log::warning('Bank details TPIN verification failed - session expired', ['user_id' => $user->id]);

            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again.',
            ], 400);
        }

        // Check if PIN is locked
        if ($user->isPinLocked()) {
            $minutesRemaining = $user->getPinLockoutTimeRemaining();

            Log::warning('Bank details TPIN verification blocked - account locked', [
                'user_id' => $user->id,
                'minutes_remaining' => $minutesRemaining,
            ]);

            return response()->json([
                'success' => false,
                'message' => "Account locked due to too many failed attempts. Please try again in {$minutesRemaining} minutes.",
                'locked' => true,
                'minutes_remaining' => $minutesRemaining,
            ], 429);
        }

        // Verify TPIN
        if (! $user->verifyPin($validated['pin'])) {
            $attemptsRemaining = config('firebase.pin.max_attempts', 5) - $user->pin_attempts;

            Log::warning('Bank details TPIN verification failed - invalid PIN', [
                'user_id' => $user->id,
                'attempts_remaining' => $attemptsRemaining,
                'ip' => $request->ip(),
            ]);

            // Check if account is now locked after this failed attempt
            if ($user->fresh()->isPinLocked()) {
                $minutesRemaining = $user->getPinLockoutTimeRemaining();

                return response()->json([
                    'success' => false,
                    'message' => "Too many failed attempts. Account locked for {$minutesRemaining} minutes.",
                    'locked' => true,
                    'minutes_remaining' => $minutesRemaining,
                ], 429);
            }

            return response()->json([
                'success' => false,
                'message' => "Invalid TPIN. {$attemptsRemaining} attempts remaining.",
                'attempts_remaining' => $attemptsRemaining,
            ], 401);
        }

        // TPIN verified successfully - update bank details
        try {
            DB::beginTransaction();

            // Get or create profile
            $profile = CustomerProfile::firstOrCreate(
                ['user_id' => $user->id],
                ['profile_completed' => 0]
            );

            // Store old values for audit log
            $oldValues = [
                'bank_name' => $profile->bank_name,
                'account_number' => $profile->account_number,
                'account_title' => $profile->account_title,
                'iban' => $profile->iban,
            ];

            // Determine if this is a create or update
            $action = $profile->bank_name ? 'updated' : 'created';

            // Update bank details
            $profile->bank_name = $pendingBankData['bank_name'];
            $profile->account_number = $pendingBankData['account_number'];
            $profile->account_title = $pendingBankData['account_title'];
            $profile->iban = $pendingBankData['iban'];
            $profile->bank_details_last_updated = now();
            $profile->withdrawal_locked_until = now()->addHours(24);
            $profile->save();

            // Log change to audit trail with TPIN auth method
            BankDetailsHistory::logChange(
                userId: $user->id,
                action: $action.'_via_tpin',
                oldValues: $action === 'updated' ? $oldValues : null,
                newValues: array_merge($pendingBankData, ['auth_method' => 'tpin']),
                ipAddress: $request->ip(),
                userAgent: $request->userAgent()
            );

            DB::commit();

            // Clear session
            session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at', 'otp_channel', 'is_ufone_bypass', 'ufone_otp_code']);

            Log::info('Bank details updated successfully via TPIN', [
                'user_id' => $user->id,
                'action' => $action,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully using TPIN! Withdrawals will be locked for 24 hours for security.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Bank details TPIN update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating bank details. Please try again.',
            ], 500);
        }
    }

    public function cancelBankDetailsOtp()
    {
        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at', 'otp_channel', 'is_ufone_bypass', 'ufone_otp_code']);

        return redirect()->route('customer.profile');
    }

    public function wallet()
    {
        $user = Auth::user();

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        $withdrawals = WithdrawalRequest::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        // Load withdrawal settings for display
        $settings = \App\Models\WithdrawalSettings::getSettings();

        // Calculate remaining limits
        $todayTotal = WithdrawalRequest::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');
        $remainingDaily = max(0, $settings->max_per_day - $todayTotal);

        $monthTotal = WithdrawalRequest::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');
        $remainingMonthly = max(0, $settings->max_per_month - $monthTotal);

        return view('customer.wallet', compact('wallet', 'withdrawals', 'settings', 'remainingDaily', 'remainingMonthly'));
    }

    public function requestWithdrawal(Request $request)
    {
        $user = Auth::user();

        // Load withdrawal settings
        $settings = \App\Models\WithdrawalSettings::getSettings();

        // Check if withdrawals are globally enabled
        if (!$settings->enabled) {
            return redirect()->back()->with('error', 'Withdrawals are temporarily disabled. Please try again later.');
        }

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                "min:{$settings->min_amount}",
                "max:{$settings->max_per_withdrawal}",
            ],
        ]);

        $wallet = Wallet::where('user_id', $user->id)->first();

        // Check if user has sufficient balance
        if (! $wallet || $wallet->balance < $validated['amount']) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        // Check if bank details exist
        $profile = CustomerProfile::where('user_id', $user->id)->first();
        if (! $profile || ! $profile->bank_name || ! $profile->account_number) {
            return redirect()->route('customer.profile')->with('error', 'Please add your bank details first!');
        }

        // Check if withdrawals are locked due to bank detail change
        if ($profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now()) {
            $hoursRemaining = now()->diffInHours($profile->withdrawal_locked_until);
            $minutesRemaining = now()->diffInMinutes($profile->withdrawal_locked_until) % 60;

            return redirect()->back()->with('error',
                "Withdrawals are locked for security after changing bank details. Please try again in {$hoursRemaining} hours and {$minutesRemaining} minutes.");
        }

        // Check daily limit
        $todayTotal = WithdrawalRequest::where('user_id', $user->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');

        if (($todayTotal + $validated['amount']) > $settings->max_per_day) {
            $remaining = $settings->max_per_day - $todayTotal;
            return redirect()->back()->with('error',
                "Daily withdrawal limit exceeded. You have Rs. " . number_format($remaining, 2) . " remaining today.");
        }

        // Check monthly limit
        $monthTotal = WithdrawalRequest::where('user_id', $user->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');

        if (($monthTotal + $validated['amount']) > $settings->max_per_month) {
            $remaining = $settings->max_per_month - $monthTotal;
            return redirect()->back()->with('error',
                "Monthly withdrawal limit exceeded. You have Rs. " . number_format($remaining, 2) . " remaining this month.");
        }

        // Check time gap between withdrawals
        if ($settings->min_gap_hours > 0) {
            $lastWithdrawal = WithdrawalRequest::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subHours($settings->min_gap_hours))
                ->latest()
                ->first();

            if ($lastWithdrawal) {
                $hoursRemaining = $settings->min_gap_hours - now()->diffInHours($lastWithdrawal->created_at);
                $minutesRemaining = now()->diffInMinutes($lastWithdrawal->created_at->addHours($settings->min_gap_hours));

                return redirect()->back()->with('error',
                    "Please wait {$hoursRemaining} hours and " . ($minutesRemaining % 60) . " minutes before requesting another withdrawal.");
            }
        }

        // Run fraud detection
        list($fraudScore, $fraudFlags) = \App\Services\FraudDetectionService::analyze($user, $validated['amount'], $request);

        // DEDUCT WALLET BALANCE IMMEDIATELY
        try {
            $wallet->debit(
                $validated['amount'],
                'withdrawal_pending',
                null, // referenceId - will be set after withdrawal request is created
                "Withdrawal request for Rs. " . number_format($validated['amount'], 2)
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }

        // Create withdrawal request
        $withdrawal = WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'bank_name' => $profile->bank_name,
            'account_number' => $profile->account_number,
            'account_title' => $profile->account_title,
            'iban' => $profile->iban,
            'status' => 'pending',
            'fraud_score' => $fraudScore,
            'fraud_flags' => !empty($fraudFlags) ? json_encode($fraudFlags) : null,
        ]);

        // Update the wallet transaction with the withdrawal reference ID
        $wallet->transactions()
            ->where('transaction_type', 'withdrawal_pending')
            ->whereNull('reference_id')
            ->latest()
            ->first()
            ->update(['reference_id' => $withdrawal->id]);

        // Send email notification
        try {
            if ($user->email) {
                \Mail::to($user->email)->send(new \App\Mail\WithdrawalRequestedMail($withdrawal));
            }
        } catch (\Exception $e) {
            \Log::error('Failed to send withdrawal request email: ' . $e->getMessage());
        }

        $message = 'Withdrawal request submitted successfully! Amount has been deducted from your wallet.';

        // Add fraud warning if flagged
        if (\App\Services\FraudDetectionService::requiresManualReview($fraudScore, $fraudFlags)) {
            $message .= ' Your request requires additional verification and may take longer to process.';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Cancel a pending withdrawal request
     */
    public function cancelWithdrawal($id)
    {
        $user = Auth::user();
        $withdrawal = WithdrawalRequest::where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        // Only pending withdrawals can be cancelled
        if (!$withdrawal->canBeCancelled()) {
            return redirect()->back()->with('error', 'This withdrawal request cannot be cancelled.');
        }

        try {
            \DB::beginTransaction();

            // REFUND the wallet balance
            $wallet = $user->wallet;
            $wallet->credit(
                $withdrawal->amount,
                'withdrawal_cancelled',
                $withdrawal->id,
                "Withdrawal request cancelled - Rs. " . number_format($withdrawal->amount, 2) . " refunded"
            );

            // Fix the totals: This is a refund, not new earnings
            $wallet->total_earned -= $withdrawal->amount;  // Remove from earned (was incorrectly added by credit())
            $wallet->total_withdrawn -= $withdrawal->amount;  // Reverse the withdrawal that never happened
            $wallet->save();

            // Update withdrawal status
            $withdrawal->update(['status' => 'cancelled']);

            \DB::commit();

            return redirect()->back()->with('success', 'Withdrawal cancelled successfully. Amount refunded to your wallet.');

        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->with('error', 'Failed to cancel withdrawal: ' . $e->getMessage());
        }
    }

    public function purchaseHistory()
    {
        $user = Auth::user();

        $purchases = PurchaseHistory::where('user_id', $user->id)
            ->with(['brand', 'partnerTransaction.partner.partnerProfile'])
            ->latest('purchase_date')
            ->paginate(15);

        // Calculate total spent and total cashback
        $totalSpent = PurchaseHistory::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('amount');

        $totalCashback = PurchaseHistory::where('user_id', $user->id)
            ->where('status', 'confirmed')
            ->sum('cashback_amount');

        return view('customer.purchase-history', compact('purchases', 'totalSpent', 'totalCashback'));
    }

    /**
     * Get pending transactions (for AJAX polling)
     */
    public function getPendingTransactions()
    {
        $user = Auth::user();

        $transactions = PartnerTransaction::where('customer_id', $user->id)
            ->where('status', 'pending_confirmation')
            ->with('partner.partnerProfile')
            ->latest()
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'transaction_code' => $transaction->transaction_code,
                    'invoice_amount' => $transaction->invoice_amount,
                    'partner_name' => $transaction->partner->partnerProfile->business_name ?? 'Unknown Partner',
                    'transaction_date' => $transaction->transaction_date,
                    'confirmation_deadline' => $transaction->confirmation_deadline,
                    'seconds_remaining' => max(0, $transaction->confirmation_deadline->diffInSeconds(now(), false)),
                    'is_expired' => $transaction->isExpired(),
                ];
            });

        return response()->json([
            'success' => true,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Confirm a partner transaction
     */
    public function confirmTransaction(Request $request, $transactionId)
    {
        $user = Auth::user();

        $transaction = PartnerTransaction::where('id', $transactionId)
            ->where('customer_id', $user->id)
            ->where('status', 'pending_confirmation')
            ->first();

        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already processed.',
            ], 404);
        }

        if ($transaction->isExpired()) {
            // Auto-confirm if expired
            $transaction->autoConfirm();

            return response()->json([
                'success' => true,
                'message' => 'Transaction was auto-confirmed (time expired).',
                'method' => 'auto',
            ]);
        }

        // Manual confirmation
        if ($transaction->confirmByCustomer()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction confirmed successfully!',
                'method' => 'manual',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to confirm transaction.',
        ], 500);
    }

    /**
     * Reject a partner transaction
     */
    public function rejectTransaction(Request $request, $transactionId)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $user = Auth::user();

        $transaction = PartnerTransaction::where('id', $transactionId)
            ->where('customer_id', $user->id)
            ->where('status', 'pending_confirmation')
            ->first();

        if (! $transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already processed.',
            ], 404);
        }

        if ($transaction->reject($validated['reason'])) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction rejected successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to reject transaction.',
        ], 500);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
