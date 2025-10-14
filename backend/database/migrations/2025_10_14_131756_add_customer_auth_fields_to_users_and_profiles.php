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
        // Add phone and PIN fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 20)->unique()->nullable()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('phone');
            $table->string('pin_hash')->nullable()->after('password');
            $table->integer('pin_attempts')->default(0)->after('pin_hash');
            $table->timestamp('pin_locked_until')->nullable()->after('pin_attempts');
        });

        // Add phone verification fields to customer_profiles table
        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->boolean('phone_verified')->default(false)->after('phone');
            $table->timestamp('last_otp_sent_at')->nullable()->after('phone_verified');
            $table->integer('otp_attempts_today')->default(0)->after('last_otp_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'phone_verified_at', 'pin_hash', 'pin_attempts', 'pin_locked_until']);
        });

        Schema::table('customer_profiles', function (Blueprint $table) {
            $table->dropColumn(['phone_verified', 'last_otp_sent_at', 'otp_attempts_today']);
        });
    }
};
