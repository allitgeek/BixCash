<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerWallet;
use App\Models\WithdrawalRequest;
use App\Models\PurchaseHistory;
use App\Models\CustomerProfile;
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
        $wallet = CustomerWallet::firstOrCreate(
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

        // Check if profile is complete
        $profileComplete = $profile && $profile->profile_completed;

        return view('customer.dashboard', compact('user', 'wallet', 'profile', 'recentPurchases', 'recentWithdrawals', 'profileComplete'));
    }

    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'date_of_birth' => 'nullable|date|before:today',
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
            'date_of_birth' => 'nullable|date|before:today',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $user = Auth::user();

        // Update user
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        // Update profile
        CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $validated['phone'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'address' => $validated['address'] ?? null,
                'city' => $validated['city'] ?? null,
                'state' => $validated['state'] ?? null,
                'postal_code' => $validated['postal_code'] ?? null,
            ]
        );

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

        $user = Auth::user();
        $profile = CustomerProfile::where('user_id', $user->id)->first();

        // Generate 6-digit OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP and pending bank details
        $profile->bank_change_otp = $otp;
        $profile->bank_change_otp_expires_at = now()->addMinutes(10);
        $profile->save();

        // Store pending bank details in session
        session([
            'pending_bank_data' => $validated,
            'show_otp_modal' => true
        ]);

        // TODO: Send SMS with OTP using Firebase or SMS service
        // For now, we'll just flash it (remove in production)
        if (app()->environment('local', 'development')) {
            session()->flash('otp_debug', $otp);
        }

        return redirect()->back();
    }

    public function verifyBankDetailsOtp(Request $request)
    {
        $request->validate([
            'otp_digit_1' => 'required|numeric|digits:1',
            'otp_digit_2' => 'required|numeric|digits:1',
            'otp_digit_3' => 'required|numeric|digits:1',
            'otp_digit_4' => 'required|numeric|digits:1',
            'otp_digit_5' => 'required|numeric|digits:1',
            'otp_digit_6' => 'required|numeric|digits:1',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_title' => 'required|string|max:255',
            'iban' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();
        $profile = CustomerProfile::where('user_id', $user->id)->first();

        // Concatenate OTP digits
        $enteredOtp = $request->otp_digit_1 . $request->otp_digit_2 . $request->otp_digit_3 .
                     $request->otp_digit_4 . $request->otp_digit_5 . $request->otp_digit_6;

        // Verify OTP
        if (!$profile || $profile->bank_change_otp !== $enteredOtp) {
            return redirect()->back()->with('error', 'Invalid OTP. Please try again.');
        }

        if ($profile->bank_change_otp_expires_at < now()) {
            return redirect()->back()->with('error', 'OTP has expired. Please request a new one.');
        }

        // Update bank details
        $profile->update([
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_title' => $request->account_title,
            'iban' => $request->iban,
            'bank_details_last_updated' => now(),
            'withdrawal_locked_until' => now()->addHours(24),
            'bank_change_otp' => null,
            'bank_change_otp_expires_at' => null,
        ]);

        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_debug']);

        return redirect()->route('customer.profile')->with('success', 'Bank details updated successfully! Withdrawals will be locked for 24 hours for security.');
    }

    public function cancelBankDetailsOtp()
    {
        $user = Auth::user();
        $profile = CustomerProfile::where('user_id', $user->id)->first();

        // Clear OTP from database
        if ($profile) {
            $profile->update([
                'bank_change_otp' => null,
                'bank_change_otp_expires_at' => null,
            ]);
        }

        // Clear session
        session()->forget(['pending_bank_data', 'show_otp_modal', 'otp_debug']);

        return redirect()->route('customer.profile');
    }

    public function wallet()
    {
        $user = Auth::user();

        $wallet = CustomerWallet::firstOrCreate(
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
        $wallet = CustomerWallet::where('user_id', $user->id)->first();

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

        return view('customer.purchase-history', compact('purchases'));
    }
}
