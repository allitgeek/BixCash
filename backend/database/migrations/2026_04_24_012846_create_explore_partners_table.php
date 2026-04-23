<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('explore_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->enum('type', ['online', 'offline']);
            $table->string('category_tag', 80)->nullable();
            $table->string('logo_path', 255)->nullable();
            $table->text('short_description')->nullable();
            $table->string('visit_url', 500)->nullable();
            $table->string('branch_count_label', 80)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'is_active', 'display_order'], 'explore_partners_listing_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('explore_partners');
    }
};
