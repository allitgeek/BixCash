<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PartnerTransaction;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\DB;

class AssignProfitSharingLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'profit-sharing:assign-levels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Qualify users for profit sharing and assign FIFO levels based on criteria';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting profit sharing level assignment...');

        // Step 1: Qualify users based on monthly criteria
        $this->info('Step 1: Checking qualification criteria...');
        $newlyQualified = $this->qualifyUsers();
        $this->info("✓ {$newlyQualified} new users qualified for profit sharing");

        // Step 2: Assign levels using FIFO queue
        $this->info('Step 2: Assigning FIFO levels...');
        $this->assignLevels();
        $this->info('✓ Levels assigned successfully');

        $this->info('Profit sharing level assignment completed!');
        return 0;
    }

    /**
     * Qualify users based on monthly criteria and set qualified_at for new qualifiers
     */
    private function qualifyUsers(): int
    {
        $currentYear = now()->year;
        $currentMonth = now()->month;
        $newlyQualified = 0;

        // Get criteria settings
        $minSpending = floatval(SystemSetting::get('active_customer_min_spending', 0));
        $minCustomers = intval(SystemSetting::get('active_partner_min_customers', 0));
        $minAmount = floatval(SystemSetting::get('active_partner_min_amount', 0));

        // Qualify customers based on spending
        $qualifiedCustomers = User::whereHas('role', function($q) {
            $q->where('name', 'customer');
        })
        ->whereNull('profit_sharing_qualified_at')
        ->get()
        ->filter(function($customer) use ($currentYear, $currentMonth, $minSpending) {
            $totalSpending = PartnerTransaction::where('customer_id', $customer->id)
                ->where('status', 'confirmed')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $currentMonth)
                ->sum('invoice_amount');

            return floatval($totalSpending) >= $minSpending;
        });

        foreach ($qualifiedCustomers as $customer) {
            $customer->profit_sharing_qualified_at = now();
            $customer->save();
            $newlyQualified++;
        }

        // Qualify partners based on BOTH conditions
        $qualifiedPartners = User::whereHas('role', function($q) {
            $q->where('name', 'partner');
        })
        ->whereNull('profit_sharing_qualified_at')
        ->get()
        ->filter(function($partner) use ($currentYear, $currentMonth, $minCustomers, $minAmount) {
            $stats = PartnerTransaction::where('partner_id', $partner->id)
                ->where('status', 'confirmed')
                ->whereYear('transaction_date', $currentYear)
                ->whereMonth('transaction_date', $currentMonth)
                ->selectRaw('COUNT(DISTINCT customer_id) as unique_customers, COALESCE(SUM(invoice_amount), 0) as total_amount')
                ->first();

            $uniqueCustomers = intval($stats->unique_customers ?? 0);
            $totalAmount = floatval($stats->total_amount ?? 0);

            return ($uniqueCustomers >= $minCustomers) && ($totalAmount >= $minAmount);
        });

        foreach ($qualifiedPartners as $partner) {
            $partner->profit_sharing_qualified_at = now();
            $partner->save();
            $newlyQualified++;
        }

        return $newlyQualified;
    }

    /**
     * Assign levels based on FIFO queue (qualified_at DESC - newest first) and admin thresholds
     * Level 1 = Newest users (entry level)
     * Level 7 = Oldest users (most senior, graduated first)
     */
    private function assignLevels(): void
    {
        // Get all qualified users ordered by qualification date (newest first for FIFO)
        $qualifiedUsers = User::whereNotNull('profit_sharing_qualified_at')
            ->orderBy('profit_sharing_qualified_at', 'DESC')
            ->get();

        if ($qualifiedUsers->isEmpty()) {
            $this->warn('No qualified users found');
            return;
        }

        // Get level thresholds from admin settings
        $levelThresholds = [];
        for ($i = 1; $i <= 7; $i++) {
            $levelThresholds[$i] = intval(SystemSetting::get("customer_threshold_level_{$i}", 0));
        }

        $this->info('Level thresholds: ' . json_encode($levelThresholds));

        // Assign levels based on FIFO queue
        $currentLevel = 1;
        $currentLevelCount = 0;
        $levelCapacity = $levelThresholds[$currentLevel];

        foreach ($qualifiedUsers as $index => $user) {
            // Move to next level if current level is full
            if ($levelCapacity > 0 && $currentLevelCount >= $levelCapacity && $currentLevel < 7) {
                $currentLevel++;
                $currentLevelCount = 0;
                $levelCapacity = $levelThresholds[$currentLevel];
            }

            // Assign level to user
            $user->profit_sharing_level = $currentLevel;
            $user->save();

            $currentLevelCount++;
        }

        $this->info("Assigned levels to {$qualifiedUsers->count()} users");
    }
}
