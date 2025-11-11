<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('withdrawal_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_amount', 15, 2)->default(100.00)->comment('Minimum withdrawal amount');
            $table->decimal('max_per_withdrawal', 15, 2)->default(50000.00)->comment('Maximum per single withdrawal');
            $table->decimal('max_per_day', 15, 2)->default(100000.00)->comment('Maximum total withdrawals per day');
            $table->decimal('max_per_month', 15, 2)->default(500000.00)->comment('Maximum total withdrawals per month');
            $table->integer('min_gap_hours')->default(6)->comment('Minimum hours between withdrawals');
            $table->boolean('enabled')->default(true)->comment('Enable/disable withdrawals globally');
            $table->text('processing_message')->nullable()->comment('Message shown to customers about processing time');
            $table->timestamps();
        });

        // Insert default settings
        DB::table('withdrawal_settings')->insert([
            'min_amount' => 100.00,
            'max_per_withdrawal' => 50000.00,
            'max_per_day' => 100000.00,
            'max_per_month' => 500000.00,
            'min_gap_hours' => 6,
            'enabled' => true,
            'processing_message' => 'Withdrawal requests are typically processed within 24-48 business hours.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawal_settings');
    }
};
