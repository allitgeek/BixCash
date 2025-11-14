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
        Schema::create('commission_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('batch_id')->constrained('commission_batches')->onDelete('cascade');
            $table->string('batch_period', 7)->comment('YYYY-MM format');
            $table->decimal('commission_rate_used', 5, 2)->comment('Rate at time of calculation');

            // Transaction summary
            $table->integer('total_transactions')->default(0);
            $table->decimal('total_invoice_amount', 15, 2)->default(0);

            // Commission amounts
            $table->decimal('commission_owed', 15, 2)->default(0)->comment('Total commission for this period');
            $table->decimal('amount_paid', 15, 2)->default(0)->comment('Amount settled so far');
            $table->decimal('amount_outstanding', 15, 2)->default(0)->comment('Still owed');

            // Status tracking
            $table->enum('status', ['pending', 'partial', 'settled', 'cancelled'])->default('pending');
            $table->timestamp('fully_settled_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('partner_id');
            $table->index('batch_id');
            $table->index('batch_period');
            $table->index('status');
            $table->index(['partner_id', 'batch_period']);
            $table->index(['status', 'amount_outstanding']);

            // Unique constraint - one ledger per partner per period
            $table->unique(['partner_id', 'batch_period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_ledgers');
    }
};
