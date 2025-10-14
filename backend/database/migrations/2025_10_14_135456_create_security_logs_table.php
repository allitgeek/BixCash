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
        Schema::create('security_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->index(); // failed_login, excessive_otp_requests, pin_lockout, etc.
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->json('data')->nullable(); // Additional context data
            $table->timestamps();

            // Index for quick lookups
            $table->index('created_at');
            $table->index(['ip_address', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_logs');
    }
};
