<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\CommissionLedger;
use App\Models\CommissionSettlement;
use App\Models\CommissionTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommissionController extends Controller
{
    /**
     * Commission overview - Partner's commission history
     */
    public function index()
    {
        $partner = Auth::user();
        $partnerProfile = $partner->partnerProfile;

        if (!$partnerProfile) {
            abort(404, 'Partner profile not found');
        }

        // Get all ledgers for this partner
        $ledgers = CommissionLedger::with(['batch', 'settlements'])
            ->where('partner_id', $partner->id)
            ->orderBy('batch_period', 'desc')
            ->get();

        // Get all settlements
        $settlements = CommissionSettlement::with(['ledger.batch'])
            ->where('partner_id', $partner->id)
            ->orderBy('processed_at', 'desc')
            ->limit(10)
            ->get();

        // Summary stats
        $totalCommissionOwed = $ledgers->sum('commission_owed');
        $totalPaid = $ledgers->sum('amount_paid');
        $totalOutstanding = $ledgers->sum('amount_outstanding');

        // Current commission rate
        $commissionRate = $partnerProfile->commission_rate;

        return view('partner.commissions.index', compact(
            'ledgers',
            'settlements',
            'totalCommissionOwed',
            'totalPaid',
            'totalOutstanding',
            'commissionRate',
            'partnerProfile'
        ));
    }

    /**
     * Show commission details for a specific period (ledger)
     */
    public function show($ledgerId)
    {
        $partner = Auth::user();

        $ledger = CommissionLedger::with([
            'batch',
            'commissionTransactions.partnerTransaction',
            'settlements.processedByUser'
        ])
            ->where('partner_id', $partner->id)
            ->findOrFail($ledgerId);

        // Get transaction details
        $transactions = $ledger->commissionTransactions()
            ->with('partnerTransaction.customer')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('partner.commissions.show', compact('ledger', 'transactions'));
    }

    /**
     * Download commission invoice (PDF-ready view)
     */
    public function downloadInvoice($ledgerId)
    {
        $partner = Auth::user();

        $ledger = CommissionLedger::with([
            'batch',
            'partner.partnerProfile',
            'commissionTransactions.partnerTransaction',
            'settlements'
        ])
            ->where('partner_id', $partner->id)
            ->findOrFail($ledgerId);

        // Return a print-ready view
        return view('partner.commissions.invoice', compact('ledger'));
    }
}
