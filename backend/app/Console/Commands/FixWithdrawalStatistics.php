<?php

namespace App\Console\Commands;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Console\Command;

class FixWithdrawalStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:withdrawal-statistics {--dry-run : Show what would be fixed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix wallet statistics for partners/customers with incorrect total_withdrawn values';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
            $this->newLine();
        }

        $this->info('🔧 Fixing Withdrawal Statistics...');
        $this->newLine();

        // Get all wallets
        $wallets = Wallet::all();
        $fixed = 0;
        $alreadyCorrect = 0;

        foreach ($wallets as $wallet) {
            // Calculate actual total withdrawn from wallet transactions
            $actualWithdrawn = abs(
                WalletTransaction::where('wallet_id', $wallet->id)
                    ->whereIn('transaction_type', ['withdrawal_pending', 'withdrawal_completed', 'withdrawal_cancelled', 'withdrawal_rejected'])
                    ->sum('amount')
            );

            // Check if it matches
            if ($wallet->total_withdrawn != $actualWithdrawn) {
                $userName = $wallet->user->name ?? "User #{$wallet->user_id}";
                $this->warn("❌ Wallet ID {$wallet->id} ({$userName})");
                $this->line("   Current total_withdrawn: Rs " . number_format($wallet->total_withdrawn, 2));
                $this->line("   Actual from transactions: Rs " . number_format($actualWithdrawn, 2));
                $this->line("   Difference: Rs " . number_format(abs($wallet->total_withdrawn - $actualWithdrawn), 2));

                if (!$dryRun) {
                    $wallet->total_withdrawn = $actualWithdrawn;
                    $wallet->save();
                    $this->info("   ✅ Fixed!");
                }

                $this->newLine();
                $fixed++;
            } else {
                $alreadyCorrect++;
            }
        }

        $this->newLine();
        $this->info('📊 Summary:');
        $this->line("   Wallets checked: " . $wallets->count());
        $this->line("   Wallets already correct: {$alreadyCorrect}");
        $this->line("   Wallets " . ($dryRun ? "to be fixed" : "fixed") . ": {$fixed}");

        if ($fixed > 0 && $dryRun) {
            $this->newLine();
            $this->warn('⚠️  Run without --dry-run to apply these fixes');
        }

        // Now fix missing withdrawal_pending transactions
        $this->newLine();
        $this->info('🔧 Checking for missing withdrawal_pending transactions...');
        $this->newLine();

        $completedWithdrawals = WithdrawalRequest::whereIn('status', ['completed', 'rejected'])
            ->get();

        $missingCount = 0;
        foreach ($completedWithdrawals as $withdrawal) {
            // Check if withdrawal_pending transaction exists
            $hasPending = WalletTransaction::where('reference_id', $withdrawal->id)
                ->where('transaction_type', 'withdrawal_pending')
                ->exists();

            if (!$hasPending) {
                $userName = $withdrawal->user->name ?? "User #{$withdrawal->user_id}";
                $this->warn("❌ Withdrawal #{$withdrawal->id} missing withdrawal_pending transaction");
                $this->line("   User: {$userName}");
                $this->line("   Amount: Rs " . number_format($withdrawal->amount, 2));
                $this->line("   Status: {$withdrawal->status}");
                $this->line("   Created: {$withdrawal->created_at}");

                if (!$dryRun) {
                    // Create the missing transaction
                    WalletTransaction::create([
                        'wallet_id' => $withdrawal->user->wallet->id,
                        'user_id' => $withdrawal->user_id,
                        'transaction_type' => 'withdrawal_pending',
                        'amount' => -$withdrawal->amount,
                        'balance_before' => $withdrawal->user->wallet->balance + $withdrawal->amount,
                        'balance_after' => $withdrawal->user->wallet->balance,
                        'reference_id' => $withdrawal->id,
                        'description' => "Withdrawal request (retroactively created)",
                        'created_at' => $withdrawal->created_at,
                        'updated_at' => $withdrawal->created_at,
                    ]);
                    $this->info("   ✅ Created missing transaction!");
                }

                $this->newLine();
                $missingCount++;
            }
        }

        if ($missingCount > 0) {
            $this->info("📊 Missing transactions " . ($dryRun ? "found" : "created") . ": {$missingCount}");
        } else {
            $this->info("✅ All withdrawal requests have corresponding transactions");
        }

        if ($dryRun && ($fixed > 0 || $missingCount > 0)) {
            $this->newLine();
            $this->warn('⚠️  This was a dry run. Run the command without --dry-run to apply the fixes.');
        }

        if (!$dryRun && ($fixed > 0 || $missingCount > 0)) {
            $this->newLine();
            $this->info('✅ All fixes applied successfully!');
        }

        return 0;
    }
}
