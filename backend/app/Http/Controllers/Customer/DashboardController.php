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

        // Update user name
        $user->update(['name' => $validated['name']]);

        // Update or create profile
        $profile = CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'profile_completed' => true,
            ]
        );

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

    public function updateBankDetails(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:50',
            'account_title' => 'required|string|max:255',
            'iban' => 'nullable|string|max:50',
        ]);

        $user = Auth::user();

        CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return redirect()->back()->with('success', 'Bank details updated successfully!');
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
