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
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->timestamp('withdrawal_locked_until')->nullable()->after('iban');
            $table->timestamp('bank_details_last_updated')->nullable()->after('withdrawal_locked_until');
            $table->string('bank_change_otp', 6)->nullable()->after('bank_details_last_updated');
            $table->timestamp('bank_change_otp_expires_at')->nullable()->after('bank_change_otp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->dropColumn(['withdrawal_locked_until', 'bank_details_last_updated', 'bank_change_otp', 'bank_change_otp_expires_at']);
        });
    }
};
