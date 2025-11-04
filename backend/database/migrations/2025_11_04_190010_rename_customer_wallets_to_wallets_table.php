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
        // Rename customer_wallets table to wallets (unified for customers & partners)
        Schema::rename('customer_wallets', 'wallets');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to customer_wallets
        Schema::rename('wallets', 'customer_wallets');
    }
};
