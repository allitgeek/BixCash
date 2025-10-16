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
        Schema::create('profit_batches', function (Blueprint $table) {
            $table->id();

            // Batch Period
            $table->string('batch_period', 20)->comment('Format: 2025-01 (YYYY-MM)');
            $table->date('period_start');
            $table->date('period_end');

            // Execution
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('triggered_by', ['automatic', 'manual']);
            $table->foreignId('triggered_by_user_id')->nullable()->constrained('users')->onDelete('set null')->comment('Admin who triggered if manual');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Statistics
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_transaction_amount', 15, 2)->default(0.00);
            $table->decimal('total_profit_distributed', 15, 2)->default(0.00);
            $table->decimal('total_customer_share', 15, 2)->default(0.00);
            $table->decimal('total_partner_share', 15, 2)->default(0.00);
            $table->decimal('total_company_share', 15, 2)->default(0.00);

            // Calculation Details
            $table->json('calculation_log')->nullable()->comment('Store detailed 7-stage process');
            $table->text('error_log')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique('batch_period');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_batches');
    }
};
