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
        Schema::create('query_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_query_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Admin who replied
            $table->text('message');
            $table->timestamp('sent_at')->nullable(); // When email was sent
            $table->timestamps();

            $table->index('customer_query_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('query_replies');
    }
};
