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
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, number, boolean, json
            $table->string('group')->default('general'); // general, criteria, notifications, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Seed initial active criteria settings
        DB::table('system_settings')->insert([
            [
                'key' => 'active_customer_min_spending',
                'value' => '0',
                'type' => 'number',
                'group' => 'criteria',
                'description' => 'Minimum spending amount for a customer to be considered active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
