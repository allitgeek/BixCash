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
        Schema::create('commission_settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ledger_id')->constrained('commission_ledgers')->onDelete('cascade');
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount_settled', 15, 2)->comment('Amount paid in this settlement');

            // Payment details
            $table->enum('payment_method', ['bank_transfer', 'cash', 'wallet_deduction', 'adjustment', 'other'])->default('bank_transfer');
            $table->string('settlement_reference')->nullable()->comment('Bank reference, transaction ID, etc.');
            $table->string('proof_of_payment')->nullable()->comment('Receipt/proof file path');
            $table->text('admin_notes')->nullable();

            // Admin who processed
            $table->foreignId('processed_by')->constrained('users')->onDelete('restrict');
            $table->timestamp('processed_at');

            $table->timestamps();

            // Indexes
            $table->index('ledger_id');
            $table->index('partner_id');
            $table->index('processed_by');
            $table->index('processed_at');
            $table->index(['partner_id', 'processed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_settlements');
    }
};
