<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PartnerTransaction;
use Carbon\Carbon;

class AutoConfirmTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:auto-confirm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-confirm expired partner transactions (60 seconds after creation)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for expired transactions...');

        // Find all pending transactions where deadline has passed
        $expiredTransactions = PartnerTransaction::where('status', 'pending_confirmation')
            ->where('confirmation_deadline', '<=', Carbon::now())
            ->get();

        $confirmedCount = 0;

        foreach ($expiredTransactions as $transaction) {
            if ($transaction->isExpired()) {
                if ($transaction->autoConfirm()) {
                    $confirmedCount++;
                    $this->info("Auto-confirmed transaction {$transaction->transaction_code} (ID: {$transaction->id})");
                } else {
                    $this->error("Failed to auto-confirm transaction {$transaction->transaction_code} (ID: {$transaction->id})");
                }
            }
        }

        if ($confirmedCount > 0) {
            $this->info("✓ Auto-confirmed {$confirmedCount} expired transaction(s)");
        } else {
            $this->info("No expired transactions found");
        }

        return Command::SUCCESS;
    }
}
