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

        // Get statistics
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $partner->id)->count(),
            'total_revenue' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'total_profit' => PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->sum('partner_profit_share'),
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

        return view('partner.transaction-history', compact('transactions'));
    }

    /**
     * Profit history
     */
    public function profitHistory()
    {
        $partner = Auth::user();

        // Get completed batches where partner received profit
        $profitBatches = ProfitBatch::where('status', 'completed')
            ->whereHas('transactions', function($q) use ($partner) {
                $q->where('partner_id', $partner->id);
            })
            ->with(['transactions' => function($q) use ($partner) {
                $q->where('partner_id', $partner->id);
            }])
            ->orderBy('batch_period', 'desc')
            ->paginate(12);

        return view('partner.profit-history', compact('profitBatches'));
    }

    /**
     * Partner profile
     */
    public function profile()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        return view('partner.profile', compact('partner', 'partnerProfile'));
    }

    /**
     * Update partner profile
     */
    public function updateProfile(Request $request)
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        $validated = $request->validate([
            'contact_person_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string|max:500',
            'business_city' => 'nullable|string|max:100',
        ]);

        // Update user
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

        return redirect()->route('partner.profile')
            ->with('success', 'Profile updated successfully!');
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
