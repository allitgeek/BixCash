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
        Schema::table('commission_settlements', function (Blueprint $table) {
            $table->enum('adjustment_type', ['refund', 'correction', 'penalty', 'bonus', 'other'])
                ->nullable()
                ->after('payment_method')
                ->comment('Type of adjustment (for negative or corrective settlements)');

            $table->text('adjustment_reason')
                ->nullable()
                ->after('adjustment_type')
                ->comment('Reason for adjustment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commission_settlements', function (Blueprint $table) {
            $table->dropColumn(['adjustment_type', 'adjustment_reason']);
        });
    }
};
