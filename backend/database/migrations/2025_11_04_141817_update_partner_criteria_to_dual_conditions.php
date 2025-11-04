<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Delete old single-criteria system settings
        DB::table('system_settings')
            ->whereIn('key', ['active_partner_criteria_type', 'active_partner_min_value'])
            ->delete();

        // Insert new dual-criteria system settings
        DB::table('system_settings')->insert([
            [
                'key' => 'active_partner_min_customers',
                'value' => '0',
                'type' => 'number',
                'group' => 'criteria',
                'description' => 'Minimum number of customers/orders for a partner to be considered active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'active_partner_min_amount',
                'value' => '0',
                'type' => 'number',
                'group' => 'criteria',
                'description' => 'Minimum transaction amount for a partner to be considered active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Delete new dual-criteria settings
        DB::table('system_settings')
            ->whereIn('key', ['active_partner_min_customers', 'active_partner_min_amount'])
            ->delete();

        // Restore old single-criteria system settings
        DB::table('system_settings')->insert([
            [
                'key' => 'active_partner_criteria_type',
                'value' => 'customers',
                'type' => 'text',
                'group' => 'criteria',
                'description' => 'Type of criteria for active partners: customers or amount',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'active_partner_min_value',
                'value' => '0',
                'type' => 'number',
                'group' => 'criteria',
                'description' => 'Minimum value for active partner criteria (number of customers or transaction amount)',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
};
