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
        Schema::table('purchase_history', function (Blueprint $table) {
            $table->foreignId('partner_transaction_id')->nullable()->after('brand_id')->constrained('partner_transactions')->onDelete('set null');
            $table->boolean('confirmed_by_customer')->default(false)->after('status');
            $table->enum('confirmation_method', ['auto', 'manual'])->nullable()->after('confirmed_by_customer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchase_history', function (Blueprint $table) {
            $table->dropForeign(['partner_transaction_id']);
            $table->dropColumn([
                'partner_transaction_id',
                'confirmed_by_customer',
                'confirmation_method'
            ]);
        });
    }
};
