<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PartnerTransaction;
use App\Models\ProfitBatch;
use App\Models\BatchScheduleConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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

        // Get statistics
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $partner->id)->count(),
            'total_revenue' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'wallet_balance' => $wallet->balance,
            'total_earned' => $wallet->total_earned,
            'pending_confirmations' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'pending_confirmation')
                ->count(),
            'today_revenue' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->whereDate('transaction_date', today())
                ->sum('invoice_amount'),
            'notification_count' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'pending_confirmation')
                ->count(),
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

        return view('partner.wallet', compact('wallet', 'withdrawals', 'partner', 'partnerProfile'));
    }

    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:100',
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

        // Create withdrawal request
        \App\Models\WithdrawalRequest::create([
            'user_id' => $partner->id,
            'amount' => $validated['amount'],
            'bank_name' => $profile->bank_name,
            'account_number' => $profile->account_number,
            'account_title' => $profile->account_title ?? $profile->contact_person_name,
            'iban' => $profile->iban,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully!');
    }

    /**
     * Partner profile
     */
    public function profile()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

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
