<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionBatch;
use App\Models\CommissionLedger;
use App\Models\CommissionSettlement;
use App\Models\CommissionTransaction;
use App\Models\User;
use App\Notifications\Partner\CommissionSettlementProcessed;
use App\Notifications\Admin\SettlementProcessedConfirmation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CommissionController extends Controller
{
    /**
     * Commission Dashboard - Overview
     */
    public function index()
    {
        // Total outstanding across all partners (cached for 5 minutes)
        $totalOutstanding = \Cache::remember('commission_total_outstanding', 300, function () {
            return CommissionLedger::where('amount_outstanding', '>', 0)->sum('amount_outstanding');
        });

        // This month's commission (cached for 5 minutes)
        $thisMonthCommission = \Cache::remember('commission_this_month', 300, function () {
            $currentMonth = Carbon::now()->format('Y-m');
            $currentMonthBatch = CommissionBatch::forPeriod($currentMonth)->first();
            return $currentMonthBatch->total_commission_calculated ?? 0;
        });

        // Pending settlements count (cached for 5 minutes)
        $pendingCount = \Cache::remember('commission_pending_count', 300, function () {
            return CommissionLedger::where('status', '!=', 'settled')
                ->where('amount_outstanding', '>', 0)
                ->count();
        });

        // Total settled (all time) - cached for 5 minutes
        $totalSettled = \Cache::remember('commission_total_settled', 300, function () {
            return CommissionSettlement::sum('amount_settled');
        });

        // Recent settlements (not cached - always fresh)
        $recentSettlements = CommissionSettlement::with(['partner', 'ledger', 'processedByUser'])
            ->orderBy('processed_at', 'desc')
            ->limit(10)
            ->get();

        // Recent batches (not cached - always fresh)
        $recentBatches = CommissionBatch::orderBy('batch_period', 'desc')
            ->limit(5)
            ->get();

        // Partners with highest outstanding (cached for 10 minutes)
        $topOutstandingPartners = \Cache::remember('commission_top_outstanding', 600, function () {
            return CommissionLedger::with('partner.partnerProfile')
                ->select('partner_id', DB::raw('SUM(amount_outstanding) as total_outstanding'))
                ->where('amount_outstanding', '>', 0)
                ->groupBy('partner_id')
                ->orderBy('total_outstanding', 'desc')
                ->limit(10)
                ->get();
        });

        // Monthly commission trend (last 12 months) - cached for 10 minutes
        $commissionTrend = \Cache::remember('commission_trend_12months', 600, function () {
            $months = [];
            $amounts = [];

            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $period = $date->format('Y-m');
                $batch = CommissionBatch::forPeriod($period)->first();

                $months[] = $date->format('M Y');
                $amounts[] = $batch ? (float) $batch->total_commission_calculated : 0;
            }

            return ['labels' => $months, 'data' => $amounts];
        });

        // Settlement status breakdown - cached for 5 minutes
        $statusBreakdown = \Cache::remember('commission_status_breakdown', 300, function () {
            return [
                'pending' => CommissionLedger::where('status', 'pending')->count(),
                'partial' => CommissionLedger::where('status', 'partial')->count(),
                'settled' => CommissionLedger::where('status', 'settled')->count(),
            ];
        });

        return view('admin.commissions.dashboard', compact(
            'totalOutstanding',
            'thisMonthCommission',
            'pendingCount',
            'totalSettled',
            'recentSettlements',
            'recentBatches',
            'topOutstandingPartners',
            'commissionTrend',
            'statusBreakdown'
        ));
    }

    /**
     * List all commission batches
     */
    public function batchIndex(Request $request)
    {
        $query = CommissionBatch::query()->orderBy('batch_period', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by period
        if ($request->filled('search')) {
            $query->where('batch_period', 'like', '%' . $request->search . '%');
        }

        $batches = $query->paginate(20);

        return view('admin.commissions.batches.index', compact('batches'));
    }

    /**
     * Show commission batch details
     */
    public function batchShow($id)
    {
        $batch = CommissionBatch::with(['ledgers.partner.partnerProfile', 'triggeredBy'])
            ->findOrFail($id);

        // Get ledgers with outstanding balance first
        $ledgers = $batch->ledgers()
            ->with('partner.partnerProfile')
            ->orderByDesc('amount_outstanding')
            ->get();

        return view('admin.commissions.batches.show', compact('batch', 'ledgers'));
    }

    /**
     * Notify all partners in a batch about their commission statement
     */
    public function notifyAllPartners($id)
    {
        $batch = CommissionBatch::with('ledgers.partner')->findOrFail($id);

        if ($batch->status !== 'completed') {
            return redirect()
                ->back()
                ->with('error', 'Can only notify partners for completed batches.');
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($batch->ledgers as $ledger) {
            try {
                $ledger->partner->notify(new \App\Notifications\Partner\NewCommissionLedger($ledger));
                $successCount++;
            } catch (\Exception $e) {
                Log::error('Failed to send commission notification', [
                    'ledger_id' => $ledger->id,
                    'partner_id' => $ledger->partner_id,
                    'error' => $e->getMessage()
                ]);
                $failedCount++;
            }
        }

        if ($failedCount === 0) {
            return redirect()
                ->back()
                ->with('success', "Successfully queued {$successCount} commission notification emails.");
        } else {
            return redirect()
                ->back()
                ->with('warning', "Sent {$successCount} emails, but {$failedCount} failed. Check logs for details.");
        }
    }

    /**
     * List all partners with commission info
     */
    public function partnerIndex(Request $request)
    {
        $query = User::whereHas('partnerProfile')
            ->with('partnerProfile')
            ->withCount([
                'commissionLedgers as total_ledgers',
                'commissionLedgers as pending_ledgers' => function ($q) {
                    $q->where('amount_outstanding', '>', 0);
                }
            ])
            ->withSum([
                'commissionLedgers as total_outstanding' => function ($q) {
                    $q->where('amount_outstanding', '>', 0);
                }
            ], 'amount_outstanding');

        // Filter by outstanding only
        if ($request->has('outstanding_only')) {
            $query->having('total_outstanding', '>', 0);
        }

        // Search by name or business
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhereHas('partnerProfile', function ($pq) use ($search) {
                        $pq->where('business_name', 'like', '%' . $search . '%');
                    });
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'total_outstanding');
        if ($sortBy === 'total_outstanding') {
            $query->orderByDesc('total_outstanding');
        } elseif ($sortBy === 'name') {
            $query->orderBy('name');
        }

        $partners = $query->paginate(20);

        return view('admin.commissions.partners.index', compact('partners'));
    }

    /**
     * Show partner commission details
     */
    public function partnerShow($partnerId)
    {
        $partner = User::with('partnerProfile')->findOrFail($partnerId);

        if (!$partner->partnerProfile) {
            abort(404, 'Partner profile not found');
        }

        // Get all ledgers for this partner
        $ledgers = CommissionLedger::with(['batch', 'settlements.processedByUser'])
            ->where('partner_id', $partnerId)
            ->orderBy('batch_period', 'desc')
            ->get();

        // Get all settlements for this partner
        $settlements = CommissionSettlement::with(['ledger.batch', 'processedByUser'])
            ->where('partner_id', $partnerId)
            ->orderBy('processed_at', 'desc')
            ->get();

        // Summary stats
        $totalCommissionOwed = $ledgers->sum('commission_owed');
        $totalPaid = $ledgers->sum('amount_paid');
        $totalOutstanding = $ledgers->sum('amount_outstanding');

        return view('admin.commissions.partners.show', compact(
            'partner',
            'ledgers',
            'settlements',
            'totalCommissionOwed',
            'totalPaid',
            'totalOutstanding'
        ));
    }

    /**
     * Show settlement form
     */
    public function settlementCreate($ledgerId)
    {
        $ledger = CommissionLedger::with(['partner.partnerProfile', 'batch'])
            ->findOrFail($ledgerId);

        if ($ledger->amount_outstanding <= 0) {
            return redirect()->back()->with('error', 'This ledger is already fully settled');
        }

        return view('admin.commissions.settlements.create', compact('ledger'));
    }

    /**
     * Process settlement
     */
    public function settlementStore(Request $request, $ledgerId)
    {
        $request->validate([
            'amount_settled' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:bank_transfer,cash,wallet_deduction,adjustment,other',
            'settlement_reference' => 'nullable|string|max:255',
            'proof_of_payment' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $ledger = CommissionLedger::with('partner.partnerProfile')->findOrFail($ledgerId);

        // Validate amount doesn't exceed outstanding
        if ($request->amount_settled > $ledger->amount_outstanding) {
            return redirect()->back()->withErrors([
                'amount_settled' => 'Amount cannot exceed outstanding balance (Rs ' . number_format($ledger->amount_outstanding, 2) . ')'
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // Handle file upload
            $proofPath = null;
            if ($request->hasFile('proof_of_payment')) {
                $proofPath = $request->file('proof_of_payment')->store('commission-proofs', 'public');
            }

            // Create settlement record
            $settlement = CommissionSettlement::create([
                'ledger_id' => $ledger->id,
                'partner_id' => $ledger->partner_id,
                'amount_settled' => $request->amount_settled,
                'payment_method' => $request->payment_method,
                'settlement_reference' => $request->settlement_reference,
                'proof_of_payment' => $proofPath,
                'admin_notes' => $request->admin_notes,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update ledger
            $ledger->recordSettlement($request->amount_settled, []);

            // Mark commission transactions as settled (proportionally or all if fully paid)
            if ($ledger->isSettled()) {
                CommissionTransaction::where('ledger_id', $ledger->id)
                    ->where('is_settled', false)
                    ->update([
                        'is_settled' => true,
                        'settlement_id' => $settlement->id,
                        'settled_at' => now(),
                    ]);
            }

            DB::commit();

            // Clear commission caches
            \Cache::forget('commission_total_outstanding');
            \Cache::forget('commission_total_settled');
            \Cache::forget('commission_pending_count');
            \Cache::forget('commission_top_outstanding');

            // Send notifications
            $ledger->partner->notify(new CommissionSettlementProcessed($settlement));
            auth()->user()->notify(new SettlementProcessedConfirmation($settlement));

            Log::info("Commission settlement processed", [
                'ledger_id' => $ledger->id,
                'partner_id' => $ledger->partner_id,
                'amount' => $request->amount_settled,
                'processed_by' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.commissions.partners.show', $ledger->partner_id)
                ->with('success', 'Settlement processed successfully! Rs ' . number_format($request->amount_settled, 2) . ' has been recorded.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Commission settlement failed", [
                'ledger_id' => $ledgerId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Settlement failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Settlement history
     */
    public function settlementHistory(Request $request)
    {
        $query = CommissionSettlement::with(['partner', 'ledger.batch', 'processedByUser', 'voidedByUser'])
            ->orderBy('processed_at', 'desc');

        // Filter out voided settlements by default (unless show_voided is checked)
        if (!$request->has('show_voided')) {
            $query->notVoided();
        }

        // Filter by partner
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('processed_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('processed_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        $settlements = $query->paginate(20)->appends($request->except('page'));

        // Get partners for filter dropdown
        $partners = User::whereHas('commissionSettlements')
            ->with('partnerProfile')
            ->orderBy('name')
            ->get();

        return view('admin.commissions.settlements.history', compact('settlements', 'partners'));
    }

    /**
     * Settlement proof gallery - view all uploaded proof documents
     */
    public function proofGallery(Request $request)
    {
        $query = CommissionSettlement::with(['partner', 'ledger.batch', 'processedByUser'])
            ->whereNotNull('proof_of_payment')
            ->notVoided()
            ->orderBy('processed_at', 'desc');

        // Filter by partner
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('processed_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('processed_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        $settlements = $query->paginate(24)->appends($request->except('page'));

        // Get partners for filter dropdown
        $partners = User::whereHas('commissionSettlements', function ($q) {
                $q->whereNotNull('proof_of_payment');
            })
            ->with('partnerProfile')
            ->orderBy('name')
            ->get();

        return view('admin.commissions.settlements.proof-gallery', compact('settlements', 'partners'));
    }

    /**
     * Trigger manual commission calculation
     */
    public function triggerCalculation(Request $request)
    {
        $request->validate([
            'period' => 'required|regex:/^\d{4}-\d{2}$/',
            'force' => 'nullable|boolean',
        ]);

        try {
            $exitCode = Artisan::call('commission:calculate-monthly', [
                'period' => $request->period,
                '--force' => $request->has('force'),
                '--user-id' => auth()->id(),
            ]);

            if ($exitCode === 0) {
                $batch = CommissionBatch::forPeriod($request->period)->first();
                return redirect()
                    ->route('admin.commissions.batches.show', $batch->id)
                    ->with('success', 'Commission calculation completed successfully for ' . $request->period);
            } else {
                return redirect()->back()->with('error', 'Commission calculation failed. Check logs for details.');
            }

        } catch (\Exception $e) {
            Log::error("Manual commission calculation failed", [
                'period' => $request->period,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Calculation failed: ' . $e->getMessage());
        }
    }

    /**
     * Download commission invoice (PDF)
     */
    public function downloadInvoice($ledgerId)
    {
        $ledger = CommissionLedger::with([
            'partner.partnerProfile',
            'batch',
            'commissionTransactions.partnerTransaction',
            'settlements'
        ])->findOrFail($ledgerId);

        // For now, return a view that can be printed as PDF
        // In production, you'd use a package like dompdf or snappy
        return view('admin.commissions.invoice', compact('ledger'));
    }

    /**
     * Bulk settle multiple partners
     */
    public function bulkSettle(Request $request)
    {
        $request->validate([
            'ledger_ids' => 'required|array|min:1',
            'ledger_ids.*' => 'exists:commission_ledgers,id',
            'payment_method' => 'required|in:bank_transfer,cash,wallet_deduction,adjustment,other',
            'settlement_reference' => 'nullable|string|max:255',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $successCount = 0;
            $totalAmount = 0;

            foreach ($request->ledger_ids as $ledgerId) {
                $ledger = CommissionLedger::find($ledgerId);

                if (!$ledger || $ledger->amount_outstanding <= 0) {
                    continue;
                }

                // Settle full outstanding amount
                $settlement = CommissionSettlement::create([
                    'ledger_id' => $ledger->id,
                    'partner_id' => $ledger->partner_id,
                    'amount_settled' => $ledger->amount_outstanding,
                    'payment_method' => $request->payment_method,
                    'settlement_reference' => $request->settlement_reference,
                    'admin_notes' => $request->admin_notes,
                    'processed_by' => auth()->id(),
                    'processed_at' => now(),
                ]);

                $ledger->recordSettlement($ledger->amount_outstanding, []);

                // Mark all transactions as settled
                CommissionTransaction::where('ledger_id', $ledger->id)
                    ->where('is_settled', false)
                    ->update([
                        'is_settled' => true,
                        'settlement_id' => $settlement->id,
                        'settled_at' => now(),
                    ]);

                $successCount++;
                $totalAmount += $ledger->amount_outstanding;
            }

            DB::commit();

            // Clear commission caches
            \Cache::forget('commission_total_outstanding');
            \Cache::forget('commission_total_settled');
            \Cache::forget('commission_pending_count');
            \Cache::forget('commission_top_outstanding');

            Log::info("Bulk commission settlement", [
                'count' => $successCount,
                'total_amount' => $totalAmount,
                'processed_by' => auth()->id(),
            ]);

            return redirect()->back()->with('success',
                "Successfully settled {$successCount} commissions totaling Rs " . number_format($totalAmount, 2)
            );

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Bulk settlement failed", ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Bulk settlement failed: ' . $e->getMessage());
        }
    }

    /**
     * Export commission batches to Excel
     */
    public function exportBatches(Request $request)
    {
        $export = new \App\Exports\CommissionBatchesExport(
            $request->status,
            $request->search
        );

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            'commission-batches-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export commission ledgers to Excel
     */
    public function exportLedgers(Request $request)
    {
        $export = new \App\Exports\CommissionLedgersExport(
            $request->batch_id,
            $request->partner_id
        );

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            'commission-ledgers-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export commission settlements to Excel
     */
    public function exportSettlements(Request $request)
    {
        $export = new \App\Exports\CommissionSettlementsExport(
            $request->partner_id,
            $request->payment_method,
            $request->from_date,
            $request->to_date
        );

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            'commission-settlements-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export partner commission report to Excel
     */
    public function exportPartnerReport($partnerId)
    {
        $partner = User::findOrFail($partnerId);
        $export = new \App\Exports\PartnerCommissionReportExport($partnerId);

        return \Maatwebsite\Excel\Facades\Excel::download(
            $export,
            'partner-commission-report-' . $partner->name . '-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    /**
     * List all adjustments
     */
    public function adjustmentIndex(Request $request)
    {
        $query = CommissionSettlement::with(['partner.partnerProfile', 'ledger.batch', 'processedByUser'])
            ->whereNotNull('adjustment_type')
            ->orderBy('processed_at', 'desc');

        // Filter by partner
        if ($request->filled('partner_id')) {
            $query->where('partner_id', $request->partner_id);
        }

        // Filter by adjustment type
        if ($request->filled('adjustment_type')) {
            $query->where('adjustment_type', $request->adjustment_type);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->where('processed_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->where('processed_at', '<=', Carbon::parse($request->to_date)->endOfDay());
        }

        $adjustments = $query->paginate(20)->appends($request->except('page'));

        // Get partners who have adjustments for filter dropdown
        $partners = User::whereHas('commissionSettlements', function($q) {
            $q->whereNotNull('adjustment_type');
        })
        ->with('partnerProfile')
        ->orderBy('name')
        ->get();

        return view('admin.commissions.adjustments.index', compact('adjustments', 'partners'));
    }

    /**
     * Show adjustment creation form
     */
    public function adjustmentCreate($ledgerId)
    {
        $ledger = CommissionLedger::with(['partner.partnerProfile', 'batch'])
            ->findOrFail($ledgerId);

        return view('admin.commissions.adjustments.create', compact('ledger'));
    }

    /**
     * Store adjustment
     */
    public function adjustmentStore(Request $request, $ledgerId)
    {
        $request->validate([
            'adjustment_amount' => 'required|numeric',
            'adjustment_type' => 'required|in:refund,correction,penalty,bonus,other',
            'adjustment_reason' => 'required|string|max:1000',
            'settlement_reference' => 'nullable|string|max:255',
        ]);

        $ledger = CommissionLedger::with('partner.partnerProfile')->findOrFail($ledgerId);
        $adjustmentAmount = (float) $request->adjustment_amount;

        // Validate that adjustment doesn't create negative paid amount
        if (($ledger->amount_paid + $adjustmentAmount) < 0) {
            return redirect()->back()->withErrors([
                'adjustment_amount' => 'Adjustment cannot result in negative paid amount'
            ])->withInput();
        }

        try {
            DB::beginTransaction();

            // Create adjustment settlement
            $settlement = CommissionSettlement::create([
                'ledger_id' => $ledger->id,
                'partner_id' => $ledger->partner_id,
                'amount_settled' => $adjustmentAmount,
                'payment_method' => 'adjustment',
                'adjustment_type' => $request->adjustment_type,
                'adjustment_reason' => $request->adjustment_reason,
                'settlement_reference' => $request->settlement_reference,
                'admin_notes' => 'Adjustment: ' . $request->adjustment_type,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            // Update ledger
            $ledger->recordSettlement($adjustmentAmount, []);

            // Update related transactions if applicable
            if ($adjustmentAmount < 0 && $ledger->status !== 'settled') {
                // Negative adjustment - mark some transactions as unsettled
                CommissionTransaction::where('ledger_id', $ledger->id)
                    ->where('is_settled', true)
                    ->orderBy('settled_at', 'desc')
                    ->limit(1)
                    ->update([
                        'is_settled' => false,
                        'settlement_id' => null,
                        'settled_at' => null,
                    ]);
            }

            DB::commit();

            // Clear caches
            \Cache::forget('commission_total_outstanding');
            \Cache::forget('commission_total_settled');
            \Cache::forget('commission_pending_count');
            \Cache::forget('commission_top_outstanding');

            // Send notifications
            $ledger->partner->notify(new CommissionSettlementProcessed($settlement));
            auth()->user()->notify(new SettlementProcessedConfirmation($settlement));

            Log::info("Commission adjustment processed", [
                'ledger_id' => $ledger->id,
                'amount' => $adjustmentAmount,
                'type' => $request->adjustment_type,
                'processed_by' => auth()->id(),
            ]);

            return redirect()
                ->route('admin.commissions.partners.show', $ledger->partner_id)
                ->with('success', 'Adjustment processed successfully! ' .
                    ($adjustmentAmount >= 0 ? 'Added' : 'Deducted') . ' Rs ' . number_format(abs($adjustmentAmount), 2));

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Commission adjustment failed", [
                'ledger_id' => $ledgerId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Adjustment failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Void a settlement
     */
    public function voidSettlement(Request $request, $id)
    {
        $request->validate([
            'void_reason' => 'required|string|max:1000',
        ]);

        $settlement = CommissionSettlement::with('ledger.partner')->findOrFail($id);

        if (!$settlement->canBeVoided()) {
            return redirect()->back()->with('error', 'Settlement cannot be voided (either already voided or more than 24 hours old)');
        }

        try {
            DB::beginTransaction();

            // Mark settlement as voided
            $settlement->update([
                'voided_at' => now(),
                'voided_by' => auth()->id(),
                'void_reason' => $request->void_reason,
            ]);

            // Reverse the settlement (create negative adjustment)
            $reverseAmount = -$settlement->amount_settled;
            $settlement->ledger->recordSettlement($reverseAmount, []);

            // Update transaction settlement status if needed
            if ($settlement->ledger->status !== 'settled') {
                CommissionTransaction::where('settlement_id', $settlement->id)
                    ->update([
                        'is_settled' => false,
                        'settlement_id' => null,
                        'settled_at' => null,
                    ]);
            }

            DB::commit();

            // Clear caches
            \Cache::forget('commission_total_outstanding');
            \Cache::forget('commission_total_settled');
            \Cache::forget('commission_pending_count');
            \Cache::forget('commission_top_outstanding');

            Log::info("Settlement voided", [
                'settlement_id' => $settlement->id,
                'amount' => $settlement->amount_settled,
                'voided_by' => auth()->id(),
                'reason' => $request->void_reason,
            ]);

            return redirect()->back()->with('success', 'Settlement voided successfully! Rs ' . number_format($settlement->amount_settled, 2) . ' has been reversed.');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Settlement void failed", [
                'settlement_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with('error', 'Void failed: ' . $e->getMessage());
        }
    }
}
