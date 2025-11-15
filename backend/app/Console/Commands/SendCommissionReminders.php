<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\CommissionLedger;
use App\Notifications\Partner\OutstandingCommissionReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendCommissionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'commission:send-reminders
                            {--min-days=7 : Minimum days since oldest ledger before sending reminder}
                            {--min-amount=0 : Minimum outstanding amount to trigger reminder}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails to partners with outstanding commission payments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $minDays = $this->option('min-days');
        $minAmount = $this->option('min-amount');

        $this->info("Sending commission reminders...");
        $this->info("Minimum days: {$minDays}, Minimum amount: Rs {$minAmount}");

        // Find all partners with outstanding commissions
        $partnersWithOutstanding = User::whereHas('commissionLedgers', function ($query) {
                $query->where('amount_outstanding', '>', 0);
            })
            ->with(['commissionLedgers' => function ($query) {
                $query->where('amount_outstanding', '>', 0)
                    ->orderBy('batch_period', 'asc');
            }])
            ->get();

        $sentCount = 0;
        $skippedCount = 0;

        foreach ($partnersWithOutstanding as $partner) {
            $totalOutstanding = $partner->commissionLedgers->sum('amount_outstanding');
            $ledgerCount = $partner->commissionLedgers->count();
            $oldestLedger = $partner->commissionLedgers->first();

            if (!$oldestLedger) {
                continue;
            }

            // Calculate days since oldest ledger
            $oldestDate = Carbon::createFromFormat('Y-m', $oldestLedger->batch_period);
            $daysSinceOldest = $oldestDate->diffInDays(Carbon::now());

            // Skip if doesn't meet minimum criteria
            if ($daysSinceOldest < $minDays || $totalOutstanding < $minAmount) {
                $skippedCount++;
                continue;
            }

            // Send reminder
            try {
                $partner->notify(new OutstandingCommissionReminder(
                    $partner,
                    $totalOutstanding,
                    $oldestLedger->batch_period,
                    $ledgerCount
                ));

                $this->line("✓ Sent reminder to {$partner->name} (Rs " . number_format($totalOutstanding, 2) . ", {$ledgerCount} periods)");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send to {$partner->name}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Reminder Summary:");
        $this->table(
            ['Metric', 'Count'],
            [
                ['Partners with outstanding', $partnersWithOutstanding->count()],
                ['Reminders sent', $sentCount],
                ['Skipped (not meeting criteria)', $skippedCount],
            ]
        );

        return Command::SUCCESS;
    }
}
