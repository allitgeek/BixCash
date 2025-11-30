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
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->string('channel')->default('firebase')->after('purpose');
            $table->string('reference_id')->nullable()->after('channel');

            // Add index for faster lookups
            $table->index(['phone', 'channel']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('otp_verifications', function (Blueprint $table) {
            $table->dropIndex(['phone', 'channel']);
            $table->dropColumn(['channel', 'reference_id']);
        });
    }
};
