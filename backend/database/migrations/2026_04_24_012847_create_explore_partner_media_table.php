<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_partner_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('explore_partner_id')->constrained('explore_partners')->cascadeOnDelete();
            $table->enum('kind', ['image', 'video']);
            $table->string('image_path', 255)->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('caption', 160)->nullable();
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['explore_partner_id', 'display_order'], 'epm_ordering_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_partner_media');
    }
};
