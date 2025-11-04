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
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_title')->nullable();
            $table->string('iban')->nullable();
            $table->timestamp('withdrawal_locked_until')->nullable();
            $table->timestamp('bank_details_last_updated')->nullable();
            $table->string('bank_change_otp', 6)->nullable();
            $table->timestamp('bank_change_otp_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->dropColumn(['bank_name', 'account_number', 'account_title', 'iban', 'withdrawal_locked_until', 'bank_details_last_updated', 'bank_change_otp', 'bank_change_otp_expires_at']);
        });
    }
};
