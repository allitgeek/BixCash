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
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->decimal('total_commission_outstanding', 15, 2)->default(0)->after('total_commission_paid')->comment('Total unpaid commission');
            $table->timestamp('last_commission_settlement_at')->nullable()->after('total_commission_outstanding')->comment('Last settlement date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->dropColumn(['total_commission_outstanding', 'last_commission_settlement_at']);
        });
    }
};
