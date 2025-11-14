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
        Schema::create('commission_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_transaction_id')->constrained('partner_transactions')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('commission_batches')->onDelete('cascade');
            $table->foreignId('ledger_id')->constrained('commission_ledgers')->onDelete('cascade');

            // Transaction details
            $table->string('transaction_code', 12)->comment('BX transaction code');
            $table->decimal('invoice_amount', 15, 2);
            $table->decimal('commission_rate', 5, 2)->comment('Rate used for this transaction');
            $table->decimal('commission_amount', 15, 2)->comment('Calculated commission');

            // Settlement tracking
            $table->boolean('is_settled')->default(false);
            $table->foreignId('settlement_id')->nullable()->constrained('commission_settlements')->onDelete('set null');
            $table->timestamp('settled_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('partner_transaction_id');
            $table->index('partner_id');
            $table->index('batch_id');
            $table->index('ledger_id');
            $table->index('transaction_code');
            $table->index('is_settled');
            $table->index(['partner_id', 'is_settled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_transactions');
    }
};
