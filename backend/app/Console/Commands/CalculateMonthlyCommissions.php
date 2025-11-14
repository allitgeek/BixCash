<?php

namespace App\Console\Commands;

use App\Models\CommissionBatch;
use App\Models\CommissionLedger;
use App\Models\CommissionTransaction;
use App\Models\PartnerTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalculateMonthlyCommissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:calculate-monthly
                            {period? : The period to calculate (YYYY-MM format, defaults to last month)}
                            {--force : Force recalculation even if batch already exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate monthly commissions for all partners based on their transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Determine period
        $period = $this->argument('period') ?? Carbon::now()->subMonth()->format('Y-m');

        // Validate period format
        if (!preg_match('/^\d{4}-\d{2}$/', $period)) {
            $this->error('Invalid period format. Use YYYY-MM (e.g., 2025-11)');
            return 1;
        }

        $this->info("Calculating commissions for period: {$period}");

        // Check if batch already exists
        $existingBatch = CommissionBatch::forPeriod($period)->first();
        if ($existingBatch && !$this->option('force')) {
            $this->error("Commission batch for {$period} already exists (ID: {$existingBatch->id})");
            $this->info("Use --force to recalculate");
            return 1;
        }

        if ($existingBatch && $this->option('force')) {
            $this->warn("Deleting existing batch and recalculating...");
            $existingBatch->delete(); // Cascades to ledgers, transactions, settlements
        }

        // Parse period
        $startDate = Carbon::createFromFormat('Y-m', $period)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $period)->endOfMonth();

        $this->info("Period: {$startDate->toDateString()} to {$endDate->toDateString()}");

        try {
            DB::beginTransaction();

            // Create commission batch
            $batch = CommissionBatch::create([
                'batch_period' => $period,
                'period_start' => $startDate,
                'period_end' => $endDate,
                'status' => 'processing',
                'triggered_by' => 'automatic',
                'triggered_by_user_id' => null,
                'started_at' => now(),
            ]);

            $this->info("Created batch ID: {$batch->id}");

            // Fetch all confirmed transactions for this period
            $transactions = PartnerTransaction::where('status', 'confirmed')
                ->whereBetween('transaction_date', [$startDate, $endDate])
                ->with(['partner.partnerProfile'])
                ->get();

            if ($transactions->isEmpty()) {
                $this->warn("No confirmed transactions found for this period");
                $batch->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
                DB::commit();
                return 0;
            }

            $this->info("Found {$transactions->count()} confirmed transactions");

            // Group transactions by partner
            $partnerGroups = $transactions->groupBy('partner_id');

            $this->info("Processing {$partnerGroups->count()} partners");

            $totalCommissionCalculated = 0;
            $totalTransactionAmount = 0;
            $calculationLog = [];

            $progressBar = $this->output->createProgressBar($partnerGroups->count());
            $progressBar->start();

            foreach ($partnerGroups as $partnerId => $partnerTransactions) {
                $partner = $partnerTransactions->first()->partner;
                $partnerProfile = $partner->partnerProfile;

                if (!$partnerProfile) {
                    $this->newLine();
                    $this->warn("Partner {$partnerId} has no profile, skipping");
                    $progressBar->advance();
                    continue;
                }

                $commissionRate = $partnerProfile->commission_rate ?? 0;

                // Calculate commission for this partner
                $partnerInvoiceTotal = 0;
                $partnerCommissionTotal = 0;
                $transactionDetails = [];

                foreach ($partnerTransactions as $transaction) {
                    $invoiceAmount = $transaction->invoice_amount;
                    $commissionAmount = ($invoiceAmount * $commissionRate) / 100;

                    $partnerInvoiceTotal += $invoiceAmount;
                    $partnerCommissionTotal += $commissionAmount;

                    // Update partner_transactions.partner_profit_share
                    $transaction->update(['partner_profit_share' => $commissionAmount]);

                    $transactionDetails[] = [
                        'transaction_id' => $transaction->id,
                        'transaction_code' => $transaction->transaction_code,
                        'invoice_amount' => $invoiceAmount,
                        'commission_amount' => $commissionAmount,
                    ];
                }

                // Create commission ledger for this partner
                $ledger = CommissionLedger::create([
                    'partner_id' => $partnerId,
                    'batch_id' => $batch->id,
                    'batch_period' => $period,
                    'commission_rate_used' => $commissionRate,
                    'total_transactions' => $partnerTransactions->count(),
                    'total_invoice_amount' => $partnerInvoiceTotal,
                    'commission_owed' => $partnerCommissionTotal,
                    'amount_paid' => 0,
                    'amount_outstanding' => $partnerCommissionTotal,
                    'status' => 'pending',
                ]);

                // Create commission transactions
                foreach ($partnerTransactions as $transaction) {
                    $invoiceAmount = $transaction->invoice_amount;
                    $commissionAmount = ($invoiceAmount * $commissionRate) / 100;

                    CommissionTransaction::create([
                        'partner_transaction_id' => $transaction->id,
                        'partner_id' => $partnerId,
                        'batch_id' => $batch->id,
                        'ledger_id' => $ledger->id,
                        'transaction_code' => $transaction->transaction_code,
                        'invoice_amount' => $invoiceAmount,
                        'commission_rate' => $commissionRate,
                        'commission_amount' => $commissionAmount,
                        'is_settled' => false,
                    ]);
                }

                // Update partner profile outstanding balance
                $partnerProfile->increment('total_commission_outstanding', $partnerCommissionTotal);

                $totalCommissionCalculated += $partnerCommissionTotal;
                $totalTransactionAmount += $partnerInvoiceTotal;

                $calculationLog[] = [
                    'partner_id' => $partnerId,
                    'partner_name' => $partner->name,
                    'business_name' => $partnerProfile->business_name,
                    'commission_rate' => $commissionRate,
                    'transactions' => $partnerTransactions->count(),
                    'invoice_total' => $partnerInvoiceTotal,
                    'commission_total' => $partnerCommissionTotal,
                ];

                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine(2);

            // Update batch with totals
            $batch->update([
                'status' => 'completed',
                'completed_at' => now(),
                'total_partners' => $partnerGroups->count(),
                'total_transactions' => $transactions->count(),
                'total_transaction_amount' => $totalTransactionAmount,
                'total_commission_calculated' => $totalCommissionCalculated,
                'calculation_log' => $calculationLog,
            ]);

            DB::commit();

            $this->info("✓ Commission calculation completed successfully!");
            $this->table(
                ['Metric', 'Value'],
                [
                    ['Batch ID', $batch->id],
                    ['Period', $period],
                    ['Partners', number_format($partnerGroups->count())],
                    ['Transactions', number_format($transactions->count())],
                    ['Total Invoice Amount', 'Rs ' . number_format($totalTransactionAmount, 2)],
                    ['Total Commission', 'Rs ' . number_format($totalCommissionCalculated, 2)],
                ]
            );

            Log::info("Commission calculation completed for period {$period}", [
                'batch_id' => $batch->id,
                'partners' => $partnerGroups->count(),
                'transactions' => $transactions->count(),
                'total_commission' => $totalCommissionCalculated,
            ]);

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();

            $this->error("Commission calculation failed: {$e->getMessage()}");
            Log::error("Commission calculation failed for period {$period}", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }
}
