<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\WithdrawalRequest;
use App\Models\PurchaseHistory;
use App\Models\CustomerProfile;
use App\Models\PartnerTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // No need for constructor middleware - routes already have 'auth' middleware applied

    public function index()
    {
        $user = Auth::user();

        // Get or create wallet
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0, 'total_earned' => 0, 'total_withdrawn' => 0]
        );

        // Get profile
        $profile = CustomerProfile::where('user_id', $user->id)->first();

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

        // Check if profile is complete
        $profileComplete = $profile && $profile->profile_completed;

        return view('customer.dashboard', compact('user', 'wallet', 'profile', 'recentPurchases', 'recentWithdrawals', 'pendingTransactions', 'profileComplete'));
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
        if (!empty($validated['phone'])) {
            $profile->phone = $validated['phone'];
        }
        if (!empty($validated['date_of_birth'])) {
            $profile->date_of_birth = $validated['date_of_birth'];
        }
        if (!empty($validated['address'])) {
            $profile->address = $validated['address'];
        }
        if (!empty($validated['city'])) {
            $profile->city = $validated['city'];
        }
        if (!empty($validated['state'])) {
            $profile->state = $validated['state'];
        }
        if (!empty($validated['postal_code'])) {
            $profile->postal_code = $validated['postal_code'];
        }

        $profile->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function requestBankDetailsOtp(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_title' => 'required|string|max:255',
            'iban' => 'nullable|string|max:50',
        ]);

        // Store pending bank details in session for Firebase verification
        session([
            'pending_bank_data' => $validated,
            'show_otp_modal' => true,
        ]);

        return redirect()->back();
    }

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
            return response()->json([
                'success' => false,
                'message' => 'Phone verification failed. Please try again.'
            ], 401);
        }

        // Verify the phone matches the logged-in user's phone
        $firebasePhone = $verificationResult['data']['phone'];
        if ($firebasePhone !== $user->phone) {
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
        $profile = CustomerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['profile_completed' => 0]
        );

        // Update bank details
        $profile->bank_name = $pendingBankData['bank_name'];
        $profile->account_number = $pendingBankData['account_number'];
        $profile->account_title = $pendingBankData['account_title'];
        $profile->iban = $pendingBankData['iban'];
        $profile->bank_details_last_updated = now();
        $profile->withdrawal_locked_until = now()->addHours(24);
        $profile->save();

        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal']);

        return response()->json([
            'success' => true,
            'message' => 'Bank details updated successfully! Withdrawals will be locked for 24 hours for security.'
        ]);
    }

    public function cancelBankDetailsOtp()
    {
        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal']);

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

        return view('customer.wallet', compact('wallet', 'withdrawals'));
    }

    public function requestWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        // Check if user has sufficient balance
        if (!$wallet || $wallet->balance < $validated['amount']) {
            return redirect()->back()->with('error', 'Insufficient balance!');
        }

        // Check if bank details exist
        $profile = CustomerProfile::where('user_id', $user->id)->first();
        if (!$profile || !$profile->bank_name || !$profile->account_number) {
            return redirect()->route('customer.profile')->with('error', 'Please add your bank details first!');
        }

        // Check if withdrawals are locked due to bank detail change
        if ($profile->withdrawal_locked_until && $profile->withdrawal_locked_until > now()) {
            $hoursRemaining = now()->diffInHours($profile->withdrawal_locked_until);
            $minutesRemaining = now()->diffInMinutes($profile->withdrawal_locked_until) % 60;

            return redirect()->back()->with('error',
                "Withdrawals are locked for security after changing bank details. Please try again in {$hoursRemaining} hours and {$minutesRemaining} minutes.");
        }

        // Create withdrawal request
        WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount' => $validated['amount'],
            'bank_name' => $profile->bank_name,
            'account_number' => $profile->account_number,
            'account_title' => $profile->account_title,
            'iban' => $profile->iban,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully!');
    }

    public function purchaseHistory()
    {
        $user = Auth::user();

        $purchases = PurchaseHistory::where('user_id', $user->id)
            ->with('brand')
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
            ->map(function($transaction) {
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
            'transactions' => $transactions
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

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already processed.'
            ], 404);
        }

        if ($transaction->isExpired()) {
            // Auto-confirm if expired
            $transaction->autoConfirm();
            return response()->json([
                'success' => true,
                'message' => 'Transaction was auto-confirmed (time expired).',
                'method' => 'auto'
            ]);
        }

        // Manual confirmation
        if ($transaction->confirmByCustomer()) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction confirmed successfully!',
                'method' => 'manual'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to confirm transaction.'
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

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found or already processed.'
            ], 404);
        }

        if ($transaction->reject($validated['reason'])) {
            return response()->json([
                'success' => true,
                'message' => 'Transaction rejected successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to reject transaction.'
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
