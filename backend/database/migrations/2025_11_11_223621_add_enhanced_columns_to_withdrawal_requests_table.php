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
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            // Change status enum to include 'cancelled'
            $table->dropColumn('status');
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected', 'cancelled'])->default('pending')->after('amount');

            // Admin processing fields
            $table->string('bank_reference')->nullable()->after('iban')->comment('Bank transaction reference number');
            $table->date('payment_date')->nullable()->after('bank_reference')->comment('Date payment was made');
            $table->string('proof_of_payment')->nullable()->after('payment_date')->comment('Path to payment proof file');
            $table->text('admin_notes')->nullable()->after('proof_of_payment')->comment('Internal admin notes');
            $table->foreignId('processed_by')->nullable()->after('admin_notes')->constrained('users')->comment('Admin who processed withdrawal');

            // Fraud detection
            $table->integer('fraud_score')->default(0)->after('processed_by')->comment('Fraud risk score (0-100)');
            $table->json('fraud_flags')->nullable()->after('fraud_score')->comment('Array of fraud detection flags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->dropForeign(['processed_by']);
            $table->dropColumn([
                'bank_reference',
                'payment_date',
                'proof_of_payment',
                'admin_notes',
                'processed_by',
                'fraud_score',
                'fraud_flags',
            ]);

            // Restore old status enum
            $table->dropColumn('status');
        });

        Schema::table('withdrawal_requests', function (Blueprint $table) {
            $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending')->after('amount');
        });
    }
};
