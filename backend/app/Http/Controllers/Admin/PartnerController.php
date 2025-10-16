<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PartnerProfile;
use App\Models\PartnerTransaction;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartnerController extends Controller
{
    /**
     * Display all partners
     */
    public function index(Request $request)
    {
        $query = User::whereHas('role', function($q) {
            $q->where('name', 'partner');
        })->with('partnerProfile');

        // Filter by status
        if ($request->has('status') && $request->status !== 'all') {
            $query->whereHas('partnerProfile', function($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Search by name, phone, or business name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('partnerProfile', function($pq) use ($search) {
                      $pq->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        $partners = $query->latest()->paginate(20);

        return view('admin.partners.index', compact('partners'));
    }

    /**
     * Display pending partner applications
     */
    public function pendingApplications()
    {
        $applications = User::whereHas('role', function($q) {
            $q->where('name', 'partner');
        })->whereHas('partnerProfile', function($q) {
            $q->where('status', 'pending');
        })->with('partnerProfile')
        ->latest()
        ->paginate(20);

        return view('admin.partners.pending', compact('applications'));
    }

    /**
     * Show partner details
     */
    public function show($id)
    {
        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            abort(404, 'Partner not found');
        }

        // Get partner statistics
        $stats = [
            'total_transactions' => PartnerTransaction::where('partner_id', $id)->count(),
            'total_revenue' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'confirmed')
                ->sum('invoice_amount'),
            'total_profit' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'confirmed')
                ->sum('partner_profit_share'),
            'pending_confirmations' => PartnerTransaction::where('partner_id', $id)
                ->where('status', 'pending_confirmation')
                ->count(),
        ];

        // Recent transactions
        $recentTransactions = PartnerTransaction::where('partner_id', $id)
            ->with('customer')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.partners.show', compact('partner', 'stats', 'recentTransactions'));
    }

    /**
     * Approve partner application
     */
    public function approve(Request $request, $id)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            return back()->withErrors(['error' => 'Partner profile not found']);
        }

        if ($partnerProfile->status === 'approved') {
            return back()->with('info', 'Partner is already approved');
        }

        // Approve partner
        $partnerProfile->status = 'approved';
        $partnerProfile->approval_notes = $request->approval_notes;
        $partnerProfile->approved_at = now();
        $partnerProfile->save();

        // Activate user account
        $partner->is_active = true;
        $partner->save();

        // TODO: Send SMS notification to partner

        return redirect()->route('admin.partners.show', $id)
            ->with('success', "Partner '{$partnerProfile->business_name}' approved successfully!");
    }

    /**
     * Reject partner application
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => 'required|string|max:500',
        ]);

        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            return back()->withErrors(['error' => 'Partner profile not found']);
        }

        // Reject partner
        $partnerProfile->status = 'rejected';
        $partnerProfile->rejection_notes = $request->rejection_notes;
        $partnerProfile->save();

        // Deactivate user account
        $partner->is_active = false;
        $partner->save();

        // TODO: Send SMS notification to partner

        return redirect()->route('admin.partners.pending')
            ->with('success', "Partner application rejected");
    }

    /**
     * View all transactions for a partner
     */
    public function transactions($id)
    {
        $partner = User::with('partnerProfile')->findOrFail($id);

        if (!$partner->isPartner()) {
            abort(404, 'Partner not found');
        }

        $transactions = PartnerTransaction::where('partner_id', $id)
            ->with('customer')
            ->latest()
            ->paginate(30);

        return view('admin.partners.transactions', compact('partner', 'transactions'));
    }

    /**
     * Update partner status (activate/deactivate)
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $partner = User::findOrFail($id);

        if (!$partner->isPartner()) {
            return back()->withErrors(['error' => 'Invalid partner account']);
        }

        $partner->is_active = $request->is_active;
        $partner->save();

        $status = $request->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "Partner account {$status} successfully");
    }
}
