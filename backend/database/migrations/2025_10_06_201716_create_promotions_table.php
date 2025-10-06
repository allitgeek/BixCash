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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->index();
            $table->string('logo_path', 500)->nullable();
            $table->enum('discount_type', ['upto', 'flat'])->default('upto');
            $table->integer('discount_value')->unsigned();
            $table->string('discount_text', 100)->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('order')->default(1)->index();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');

            // Indexes for performance
            $table->index(['is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
