<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Wallet;

class BackfillUserWallets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallets:backfill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create wallets for all existing customers and partners who don\'t have one';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting wallet backfill for existing users...');

        // Get all customers and partners without wallets
        $usersWithoutWallets = User::whereHas('role', function($q) {
            $q->whereIn('name', ['customer', 'partner']);
        })
        ->whereDoesntHave('wallet')
        ->get();

        if ($usersWithoutWallets->isEmpty()) {
            $this->info('✓ All customers and partners already have wallets!');
            return 0;
        }

        $this->info("Found {$usersWithoutWallets->count()} users without wallets");

        $progressBar = $this->output->createProgressBar($usersWithoutWallets->count());
        $progressBar->start();

        $created = 0;

        foreach ($usersWithoutWallets as $user) {
            Wallet::create([
                'user_id' => $user->id,
                'balance' => 0.00,
                'total_earned' => 0.00,
                'total_withdrawn' => 0.00,
            ]);
            $created++;
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine();
        $this->info("✓ Successfully created {$created} wallets!");

        return 0;
    }
}
