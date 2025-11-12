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
            $table->dropColumn(['bank_change_otp', 'bank_change_otp_expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->string('bank_change_otp', 6)->nullable();
            $table->timestamp('bank_change_otp_expires_at')->nullable();
        });
    }
};
