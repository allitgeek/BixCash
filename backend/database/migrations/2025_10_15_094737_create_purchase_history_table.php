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
        Schema::create('purchase_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null');
            $table->string('order_id')->unique();
            $table->decimal('amount', 15, 2);
            $table->decimal('cashback_amount', 15, 2)->default(0.00);
            $table->decimal('cashback_percentage', 5, 2)->default(0.00);
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->text('description')->nullable();
            $table->timestamp('purchase_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_history');
    }
};
