<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->string('source')->default('in_app')->after('status');
            $table->string('external_order_id')->nullable()->after('source');
            $table->unique('external_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('partner_transactions', function (Blueprint $table) {
            $table->dropUnique(['external_order_id']);
            $table->dropColumn(['source', 'external_order_id']);
        });
    }
};
