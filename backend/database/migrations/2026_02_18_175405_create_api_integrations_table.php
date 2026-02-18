<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_integrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('partner_id')->unique()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('api_key', 64)->unique();
            $table->string('api_secret');
            $table->boolean('is_active')->default(true);
            $table->json('allowed_ips')->nullable();
            $table->integer('rate_limit_per_minute')->default(60);
            $table->timestamp('last_used_at')->nullable();
            $table->unsignedBigInteger('total_requests')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_integrations');
    }
};
