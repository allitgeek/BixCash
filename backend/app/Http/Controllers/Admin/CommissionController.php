<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommissionBatch;
use App\Models\CommissionLedger;
use App\Models\CommissionSettlement;
use App\Models\CommissionTransaction;
use App\Models\User;
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
        // Total outstanding across all partners
        $totalOutstanding = CommissionLedger::where('amount_outstanding', '>', 0)->sum('amount_outstanding');

        // This month's commission
        $currentMonth = Carbon::now()->format('Y-m');
        $currentMonthBatch = CommissionBatch::forPeriod($currentMonth)->first();
        $thisMonthCommission = $currentMonthBatch->total_commission_calculated ?? 0;

        // Pending settlements count
        $pendingCount = CommissionLedger::where('status', '!=', 'settled')
            ->where('amount_outstanding', '>', 0)
            ->count();

        // Total settled (all time)
        $totalSettled = CommissionSettlement::sum('amount_settled');

        // Recent settlements
        $recentSettlements = CommissionSettlement::with(['partner', 'ledger', 'processedByUser'])
            ->orderBy('processed_at', 'desc')
            ->limit(10)
            ->get();

        // Recent batches
        $recentBatches = CommissionBatch::orderBy('batch_period', 'desc')
            ->limit(5)
            ->get();

        // Partners with highest outstanding
        $topOutstandingPartners = CommissionLedger::with('partner.partnerProfile')
            ->select('partner_id', DB::raw('SUM(amount_outstanding) as total_outstanding'))
            ->where('amount_outstanding', '>', 0)
            ->groupBy('partner_id')
            ->orderBy('total_outstanding', 'desc')
            ->limit(10)
            ->get();

        return view('admin.commissions.dashboard', compact(
            'totalOutstanding',
            'thisMonthCommission',
            'pendingCount',
            'totalSettled',
            'recentSettlements',
            'recentBatches',
            'topOutstandingPartners'
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
        $query = CommissionSettlement::with(['partner', 'ledger.batch', 'processedByUser'])
            ->orderBy('processed_at', 'desc');

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

        $settlements = $query->paginate(20);

        // Get partners for filter dropdown
        $partners = User::whereHas('commissionSettlements')
            ->with('partnerProfile')
            ->orderBy('name')
            ->get();

        return view('admin.commissions.settlements.history', compact('settlements', 'partners'));
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
}
