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
        Schema::create('partner_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 12)->unique()->comment('Format: BX2025001234');

            // Parties
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->onDelete('set null');

            // Transaction Details
            $table->decimal('invoice_amount', 15, 2);
            $table->timestamp('transaction_date');

            // Confirmation Flow
            $table->enum('status', ['pending_confirmation', 'confirmed', 'rejected', 'expired', 'cancelled'])->default('pending_confirmation');
            $table->timestamp('confirmation_deadline')->comment('Transaction created + 60 seconds');
            $table->timestamp('confirmed_at')->nullable();
            $table->boolean('confirmed_by_customer')->default(false);
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();

            // Profit Sharing (filled after batch run)
            $table->timestamp('batch_processed_at')->nullable();
            $table->foreignId('batch_id')->nullable()->constrained('profit_batches')->onDelete('set null');
            $table->decimal('customer_profit_share', 15, 2)->default(0.00);
            $table->decimal('partner_profit_share', 15, 2)->default(0.00);
            $table->decimal('company_profit_share', 15, 2)->default(0.00);
            $table->json('profit_calculation_details')->nullable()->comment('Store 7-stage breakdown');

            // Audit
            $table->json('partner_device_info')->nullable();
            $table->string('customer_ip_address', 45)->nullable();

            $table->timestamps();

            // Indexes
            $table->index('partner_id');
            $table->index('customer_id');
            $table->index('status');
            $table->index('confirmation_deadline');
            $table->index('batch_id');
            $table->index('transaction_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_transactions');
    }
};
