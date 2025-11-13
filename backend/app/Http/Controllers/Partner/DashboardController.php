<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Http\Requests\BankDetailsRequest;
use App\Models\BankDetailsHistory;
use App\Models\User;
use App\Models\PartnerTransaction;
use App\Models\ProfitBatch;
use App\Models\BatchScheduleConfig;
use App\Models\PartnerProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show partner dashboard
     */
    public function index()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        // Get or create wallet
        $wallet = \App\Models\Wallet::firstOrCreate(
            ['user_id' => $partner->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Calculate pending confirmations once (optimization)
        $pendingCount = PartnerTransaction::where('partner_id', $partner->id)
            ->where('status', 'pending_confirmation')
            ->count();

        // Get statistics
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $partner->id)->count(),
            'total_revenue' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'wallet_balance' => $wallet->balance,
            'total_earned' => $wallet->total_earned,
            'pending_confirmations' => $pendingCount,
            'today_revenue' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->whereDate('transaction_date', today())
                ->sum('invoice_amount'),
            'notification_count' => $pendingCount,
        ];

        // Get time-based greeting
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'morning' : ($hour < 17 ? 'afternoon' : 'evening');

        // Get next profit batch date
        $nextBatchSchedule = BatchScheduleConfig::getActiveSchedule();
        $nextBatchDate = $nextBatchSchedule ? $nextBatchSchedule->next_scheduled_run : null;

        // Recent transactions (last 5)
        $recentTransactions = PartnerTransaction::where('partner_id', $partner->id)
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get pending transactions for notification dropdown
        $pendingTransactions = PartnerTransaction::where('partner_id', $partner->id)
            ->where('status', 'pending_confirmation')
            ->with('customer')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('partner.dashboard', compact(
            'partner',
            'partnerProfile',
            'stats',
            'wallet',
            'nextBatchDate',
            'recentTransactions',
            'greeting',
            'pendingTransactions'
        ));
    }

    /**
     * Search for customer by phone number
     */
    public function searchCustomer(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|regex:/^[0-9]{10}$/',
        ]);

        $phone = '+92' . $request->phone;

        // Find customer
        $customer = User::where('phone', $phone)
            ->whereHas('role', function($q) {
                $q->where('name', 'customer');
            })
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'No BixCash customer found with this phone number.'
            ], 404);
        }

        // Get customer stats
        $customerStats = [
            'total_purchases' => PartnerTransaction::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->count(),
            'total_spent' => PartnerTransaction::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'total_profit' => PartnerTransaction::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->sum('customer_profit_share'),
        ];

        return response()->json([
            'success' => true,
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'stats' => $customerStats,
            ]
        ]);
    }

    /**
     * Create new transaction
     */
    public function createTransaction(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'invoice_amount' => 'required|numeric|min:1|max:1000000',
        ]);

        $partner = Auth::user();

        // Verify customer role
        $customer = User::findOrFail($request->customer_id);
        if (!$customer->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer.'
            ], 400);
        }

        // Create transaction
        $transaction = PartnerTransaction::create([
            'partner_id' => $partner->id,
            'customer_id' => $customer->id,
            'invoice_amount' => $request->invoice_amount,
            'transaction_date' => now(),
            'status' => 'pending_confirmation',
            'partner_device_info' => json_encode([
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]),
            'customer_ip_address' => $request->ip(),
        ]);

        // Note: Transaction code and confirmation_deadline are auto-generated in model boot method

        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $transaction->id,
                'transaction_code' => $transaction->transaction_code,
                'invoice_amount' => $transaction->invoice_amount,
                'confirmation_deadline' => $transaction->confirmation_deadline,
                'customer_name' => $customer->name,
            ],
            'message' => 'Transaction created successfully. Customer has 60 seconds to confirm.'
        ]);
    }

    /**
     * Get transaction status (for real-time updates)
     */
    public function getTransactionStatus($transactionId)
    {
        $partner = Auth::user();

        $transaction = PartnerTransaction::where('id', $transactionId)
            ->where('partner_id', $partner->id)
            ->with('customer')
            ->firstOrFail();

        // Check if expired and auto-confirm if needed
        if ($transaction->isExpired() && $transaction->status === 'pending_confirmation') {
            $transaction->autoConfirm();
            $transaction->refresh();
        }

        return response()->json([
            'success' => true,
            'transaction' => [
                'id' => $transaction->id,
                'status' => $transaction->status,
                'confirmed_at' => $transaction->confirmed_at,
                'confirmation_deadline' => $transaction->confirmation_deadline,
                'is_expired' => $transaction->isExpired(),
            ]
        ]);
    }

    /**
     * Transaction history
     */
    public function transactionHistory(Request $request)
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        $query = PartnerTransaction::where('partner_id', $partner->id)
            ->with('customer');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('transaction_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('transaction_date', '<=', $request->to_date);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get stats for header
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $partner->id)->count(),
            'total_amount' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'confirmed_count' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->count(),
            'pending_count' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'pending_confirmation')
                ->count(),
        ];

        return view('partner.transaction-history', compact('transactions', 'stats', 'partner', 'partnerProfile'));
    }

    /**
     * Profit history - Shows monthly profit sharing distributions received
     */
    public function profitHistory()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        // Get profit sharing distributions where partner received funds
        $profitDistributions = \App\Models\WalletTransaction::where('user_id', $partner->id)
            ->where('transaction_type', 'profit_sharing')
            ->with('profitSharingDistribution')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Calculate total profit from all distributions
        $totalProfit = \App\Models\WalletTransaction::where('user_id', $partner->id)
            ->where('transaction_type', 'profit_sharing')
            ->sum('amount');

        $totalDistributions = \App\Models\WalletTransaction::where('user_id', $partner->id)
            ->where('transaction_type', 'profit_sharing')
            ->count();

        // Get last payment info
        $lastPayment = \App\Models\WalletTransaction::where('user_id', $partner->id)
            ->where('transaction_type', 'profit_sharing')
            ->latest()
            ->first();

        $stats = [
            'total_distributions' => $totalDistributions,
            'total_profit' => $totalProfit,
            'last_payment_date' => $lastPayment ? $lastPayment->created_at : null,
        ];

        return view('partner.profit-history', compact('profitDistributions', 'stats', 'partner', 'partnerProfile'));
    }

    /**
     * Partner wallet page
     */
    public function wallet()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        // Get or create wallet
        $wallet = \App\Models\Wallet::firstOrCreate(
            ['user_id' => $partner->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get withdrawal requests
        $withdrawals = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
            ->latest()
            ->paginate(10);

        // Get withdrawal settings
        $settings = \App\Models\WithdrawalSettings::getSettings();

        // Calculate remaining limits
        $todayTotal = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');

        $monthTotal = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');

        $dailyRemaining = max(0, $settings->max_per_day - $todayTotal);
        $monthlyRemaining = max(0, $settings->max_per_month - $monthTotal);

        return view('partner.wallet', compact(
            'wallet',
            'withdrawals',
            'partner',
            'partnerProfile',
            'settings',
            'dailyRemaining',
            'monthlyRemaining'
        ));
    }

    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request)
    {
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

        $partner = Auth::user();
        $wallet = \App\Models\Wallet::where('user_id', $partner->id)->first();

        // Check if user has sufficient balance
        if (!$wallet || $wallet->balance < $validated['amount']) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        // Check if bank details exist
        $profile = $partner->partnerProfile;
        if (!$profile || !$profile->bank_name || !$profile->account_number) {
            return redirect()->route('partner.profile')->with('error', 'Please add your bank details first in your profile!');
        }

        // Check if withdrawals are locked due to bank detail change
        if ($profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now()) {
            $hoursRemaining = now()->diffInHours($profile->withdrawal_locked_until);
            $minutesRemaining = now()->diffInMinutes($profile->withdrawal_locked_until) % 60;

            return redirect()->back()->with('error',
                "Withdrawals are locked for security after changing bank details. Please try again in {$hoursRemaining} hours and {$minutesRemaining} minutes.");
        }

        // Check daily limit
        $todayTotal = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
            ->whereDate('created_at', today())
            ->whereIn('status', ['pending', 'processing', 'completed'])
            ->sum('amount');

        if (($todayTotal + $validated['amount']) > $settings->max_per_day) {
            $remaining = $settings->max_per_day - $todayTotal;
            return redirect()->back()->with('error',
                "Daily withdrawal limit exceeded. You have Rs. " . number_format($remaining, 2) . " remaining today.");
        }

        // Check monthly limit
        $monthTotal = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
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
            $lastWithdrawal = \App\Models\WithdrawalRequest::where('user_id', $partner->id)
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
        list($fraudScore, $fraudFlags) = \App\Services\FraudDetectionService::analyze($partner, $validated['amount'], $request);

        // WRAP IN DATABASE TRANSACTION FOR ATOMICITY
        DB::beginTransaction();
        try {
            // DEDUCT WALLET BALANCE IMMEDIATELY (CRITICAL FIX)
            Log::info('Partner withdrawal: Starting wallet debit', [
                'user_id' => $partner->id,
                'amount' => $validated['amount'],
                'balance_before' => $wallet->balance
            ]);

            $wallet->debit(
                $validated['amount'],
                'withdrawal_pending',
                null, // referenceId - will be set after withdrawal request is created
                "Withdrawal request for Rs. " . number_format($validated['amount'], 2)
            );

            Log::info('Partner withdrawal: Wallet debited successfully', [
                'user_id' => $partner->id,
                'balance_after' => $wallet->fresh()->balance,
                'total_withdrawn' => $wallet->fresh()->total_withdrawn
            ]);

            // Create withdrawal request
            $withdrawalRequest = \App\Models\WithdrawalRequest::create([
                'user_id' => $partner->id,
                'amount' => $validated['amount'],
                'bank_name' => $profile->bank_name,
                'account_number' => $profile->account_number,
                'account_title' => $profile->account_title ?? $profile->contact_person_name,
                'iban' => $profile->iban,
                'status' => 'pending',
                'fraud_score' => $fraudScore,
                'fraud_flags' => $fraudFlags,
            ]);

            // Update the wallet transaction with withdrawal request reference
            $walletTransaction = \App\Models\WalletTransaction::where('user_id', $partner->id)
                ->where('transaction_type', 'withdrawal_pending')
                ->whereNull('reference_id')
                ->latest()
                ->first();

            if ($walletTransaction) {
                $walletTransaction->update(['reference_id' => $withdrawalRequest->id]);
            }

            DB::commit();

            Log::info('Partner withdrawal: Request created successfully', [
                'user_id' => $partner->id,
                'withdrawal_id' => $withdrawalRequest->id,
                'amount' => $validated['amount']
            ]);

            return redirect()->back()->with('success', 'Withdrawal request submitted successfully! Amount has been deducted from your wallet.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Partner withdrawal: Failed to process', [
                'user_id' => $partner->id,
                'amount' => $validated['amount'],
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Failed to process withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Partner profile
     */
    public function profile()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        // Auto-clear expired OTP modal sessions (10-minute expiration)
        if (session('show_otp_modal') && session('otp_modal_expires_at')) {
            $expiresAt = session('otp_modal_expires_at');
            if (now()->timestamp > $expiresAt) {
                session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at']);
                Log::info('Partner OTP modal session expired and cleared', [
                    'user_id' => $partner->id,
                    'expired_at' => $expiresAt
                ]);
            }
        }

        // Get wallet for earnings
        $wallet = \App\Models\Wallet::firstOrCreate(
            ['user_id' => $partner->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get quick stats for header
        $stats = [
            'total_orders' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->count(),
            'total_earned' => $wallet->total_earned,
            'member_since' => $partner->created_at,
        ];

        return view('partner.profile', compact('partner', 'partnerProfile', 'stats'));
    }

    /**
     * Update partner profile
     */
    public function updateProfile(Request $request)
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        // Log request info for debugging
        \Log::info('Partner profile update attempt', [
            'partner_id' => $partner->id,
            'has_file' => $request->hasFile('logo'),
            'logo_only' => $request->input('logo_only'),
            'contact_person_name' => $request->input('contact_person_name')
        ]);

        // If logo_only flag is set, skip other validations
        $isLogoOnly = $request->input('logo_only') == '1';

        $rules = [
            'logo' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // Max 2MB
        ];

        // Only validate other fields if not logo-only upload
        if (!$isLogoOnly) {
            $rules['contact_person_name'] = 'required|string|max:255';
            $rules['email'] = 'nullable|email|max:255';
            $rules['business_address'] = 'nullable|string|max:500';
            $rules['business_city'] = 'nullable|string|max:100';
        }

        $validated = $request->validate($rules);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            \Log::info('Processing logo upload', [
                'file_name' => $request->file('logo')->getClientOriginalName(),
                'file_size' => $request->file('logo')->getSize(),
                'mime_type' => $request->file('logo')->getMimeType()
            ]);

            // Delete old logo if exists
            if ($partnerProfile->logo) {
                $oldLogoPath = storage_path('app/public/' . $partnerProfile->logo);
                if (file_exists($oldLogoPath)) {
                    unlink($oldLogoPath);
                    \Log::info('Deleted old logo', ['path' => $oldLogoPath]);
                }
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('partner-logos', 'public');
            $partnerProfile->update(['logo' => $logoPath]);
            \Log::info('Logo uploaded successfully', ['path' => $logoPath]);

            // If logo-only upload, return early
            if ($isLogoOnly) {
                return redirect()->route('partner.profile')
                    ->with('success', 'Logo uploaded successfully!');
            }
        } else {
            \Log::info('No logo file in request');
        }

        // Update user (only if not logo-only)
        if (!$isLogoOnly) {
            $partner->update([
                'name' => $validated['contact_person_name'],
                'email' => $validated['email'] ?? null,
            ]);

            // Update partner profile
            $partnerProfile->update([
                'contact_person_name' => $validated['contact_person_name'],
                'business_address' => $validated['business_address'] ?? null,
                'business_city' => $validated['business_city'] ?? null,
            ]);
        }

        return redirect()->route('partner.profile')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Request OTP for bank details update
     */
    public function requestBankDetailsOtp(BankDetailsRequest $request)
    {
        $validated = $request->validated();
        $partner = Auth::user();

        // Store pending bank details in session for Firebase verification
        session([
            'pending_bank_data' => $validated,
            'show_otp_modal' => true,
            'otp_modal_expires_at' => now()->addMinutes(10)->timestamp, // 10-minute expiration
        ]);

        Log::info('Partner bank details OTP requested', [
            'user_id' => $partner->id,
            'bank_name' => $validated['bank_name'],
            'has_existing_details' => !is_null($partner->partnerProfile?->bank_name),
        ]);

        // Return JSON for AJAX requests (TPIN flow)
        if ($request->ajax() || $request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Bank details stored in session. Please verify.'
            ]);
        }

        // Redirect for OTP flow
        return redirect()->back();
    }

    /**
     * Verify OTP and update bank details
     */
    public function verifyBankDetailsOtp(Request $request)
    {
        $request->validate([
            'firebase_token' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify Firebase ID token
        $firebaseService = app(\App\Services\FirebaseOtpService::class);
        $verificationResult = $firebaseService->verifyFirebaseIdToken($request->firebase_token);

        if (!$verificationResult['success']) {
            Log::warning('Partner bank details OTP verification failed', [
                'user_id' => $user->id,
                'reason' => 'Firebase verification failed'
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Phone verification failed. Please try again.'
            ], 401);
        }

        // Verify the phone matches the logged-in user's phone
        $firebasePhone = $verificationResult['data']['phone'];
        if ($firebasePhone !== $user->phone) {
            Log::warning('Partner bank details phone mismatch', [
                'user_id' => $user->id,
                'expected_phone' => $user->phone,
                'firebase_phone' => $firebasePhone
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Phone number mismatch. Please use your registered phone number.'
            ], 403);
        }

        // Get pending bank data from session
        $pendingBankData = session('pending_bank_data');
        if (!$pendingBankData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again.'
            ], 400);
        }

        // Get or create profile
        $profile = $user->partnerProfile;
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Partner profile not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Store old values for audit trail
            $oldValues = [
                'bank_name' => $profile->bank_name,
                'account_number' => $profile->account_number,
                'account_title' => $profile->account_title,
                'iban' => $profile->iban,
            ];

            $action = $profile->bank_name ? 'updated' : 'created';

            // Update bank details
            $profile->bank_name = $pendingBankData['bank_name'];
            $profile->account_number = $pendingBankData['account_number'];
            $profile->account_title = $pendingBankData['account_title'];
            $profile->iban = $pendingBankData['iban'];
            $profile->bank_details_last_updated = now();
            $profile->withdrawal_locked_until = now()->addHours(24);
            $profile->save();

            // Log to audit trail
            BankDetailsHistory::logChange(
                userId: $user->id,
                action: $action,
                oldValues: $action === 'updated' ? $oldValues : null,
                newValues: $pendingBankData,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent()
            );

            DB::commit();

            Log::info('Partner bank details updated successfully via OTP', [
                'user_id' => $user->id,
                'action' => $action,
                'bank_name' => $pendingBankData['bank_name']
            ]);

            // Clear session
            session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at']);

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully! Withdrawals will be locked for 24 hours for security.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Partner bank details update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank details. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify TPIN and update bank details
     */
    public function verifyBankDetailsTpin(Request $request)
    {
        $validated = $request->validate([
            'pin' => 'required|digits:4',
        ]);

        $user = Auth::user();

        // Check if user has PIN set
        if (!$user->pin_hash) {
            return response()->json([
                'success' => false,
                'message' => 'TPIN not set. Please set up your TPIN first.'
            ], 400);
        }

        // Check if PIN is locked
        if ($user->isPinLocked()) {
            $minutesRemaining = now()->diffInMinutes($user->pin_locked_until);

            return response()->json([
                'success' => false,
                'locked' => true,
                'message' => "Too many failed attempts. Please try again in {$minutesRemaining} minutes.",
                'minutes_remaining' => $minutesRemaining
            ], 423); // 423 Locked
        }

        // Get pending bank data from session
        $pendingBankData = session('pending_bank_data');
        if (!$pendingBankData) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Please try again.'
            ], 400);
        }

        // Verify PIN (this increments attempts and locks after 5 failures)
        if (!$user->verifyPin($validated['pin'])) {
            $attemptsRemaining = 5 - $user->pin_attempts;

            // Check if user is now locked
            if ($user->fresh()->isPinLocked()) {
                return response()->json([
                    'success' => false,
                    'locked' => true,
                    'message' => 'Too many failed attempts. Account locked for 15 minutes.',
                    'minutes_remaining' => 15
                ], 423);
            }

            return response()->json([
                'success' => false,
                'message' => "Invalid TPIN. {$attemptsRemaining} attempts remaining.",
                'attempts_remaining' => $attemptsRemaining
            ], 401);
        }

        // Get partner profile
        $profile = $user->partnerProfile;
        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Partner profile not found.'
            ], 404);
        }

        try {
            DB::beginTransaction();

            // Store old values for audit trail
            $oldValues = [
                'bank_name' => $profile->bank_name,
                'account_number' => $profile->account_number,
                'account_title' => $profile->account_title,
                'iban' => $profile->iban,
            ];

            $action = $profile->bank_name ? 'updated_via_tpin' : 'created_via_tpin';

            // Update bank details
            $profile->bank_name = $pendingBankData['bank_name'];
            $profile->account_number = $pendingBankData['account_number'];
            $profile->account_title = $pendingBankData['account_title'];
            $profile->iban = $pendingBankData['iban'];
            $profile->bank_details_last_updated = now();
            $profile->withdrawal_locked_until = now()->addHours(24);
            $profile->save();

            // Log to audit trail
            BankDetailsHistory::logChange(
                userId: $user->id,
                action: $action,
                oldValues: strpos($action, 'updated') !== false ? $oldValues : null,
                newValues: $pendingBankData,
                ipAddress: $request->ip(),
                userAgent: $request->userAgent()
            );

            DB::commit();

            Log::info('Partner bank details updated successfully via TPIN', [
                'user_id' => $user->id,
                'action' => $action,
                'bank_name' => $pendingBankData['bank_name']
            ]);

            // Clear session
            session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at']);

            return response()->json([
                'success' => true,
                'message' => 'Bank details updated successfully using TPIN! Withdrawals will be locked for 24 hours for security.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Partner bank details TPIN update failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update bank details. Please try again.'
            ], 500);
        }
    }

    /**
     * Cancel bank details OTP verification
     */
    public function cancelBankDetailsOtp()
    {
        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_modal_expires_at']);

        return redirect()->route('partner.profile');
    }

    /**
     * Remove partner logo
     */
    public function removeLogo()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        if ($partnerProfile && $partnerProfile->logo) {
            // Delete logo file
            $logoPath = storage_path('app/public/' . $partnerProfile->logo);
            if (file_exists($logoPath)) {
                unlink($logoPath);
            }

            // Update database
            $partnerProfile->update(['logo' => null]);

            return redirect()->route('partner.profile')
                ->with('success', 'Logo removed successfully!');
        }

        return redirect()->route('partner.profile')
            ->with('info', 'No logo to remove.');
    }

    /**
     * Logout
     */
    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully!');
    }
}
