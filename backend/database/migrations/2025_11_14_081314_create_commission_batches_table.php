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
        Schema::create('commission_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_period', 7)->unique()->comment('YYYY-MM format');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('triggered_by', ['automatic', 'manual'])->default('manual');
            $table->foreignId('triggered_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Statistics
            $table->integer('total_partners')->default(0);
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_transaction_amount', 15, 2)->default(0);
            $table->decimal('total_commission_calculated', 15, 2)->default(0);

            // Calculation details stored as JSON
            $table->json('calculation_log')->nullable()->comment('Detailed calculation breakdown');

            $table->timestamps();

            // Indexes
            $table->index('batch_period');
            $table->index('status');
            $table->index(['status', 'batch_period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_batches');
    }
};
