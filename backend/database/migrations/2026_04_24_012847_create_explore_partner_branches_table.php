<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_partner_branches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_partner_id')->constrained('explore_partners')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('address', 255);
            $table->string('city', 80);
            $table->string('phone', 40)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->string('directions_url', 500)->nullable();
            $table->json('weekly_schedule')->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['explore_partner_id', 'display_order'], 'epb_ordering_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_partner_branches');
    }
};
