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
        Schema::table('users', function (Blueprint $table) {
            // Profit sharing level (1-7)
            $table->unsignedTinyInteger('profit_sharing_level')->nullable()->after('is_active');

            // Date when user first qualified for profit sharing
            $table->timestamp('profit_sharing_qualified_at')->nullable()->after('profit_sharing_level');

            // Indexes for performance
            $table->index('profit_sharing_level');
            $table->index('profit_sharing_qualified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['profit_sharing_level']);
            $table->dropIndex(['profit_sharing_qualified_at']);
            $table->dropColumn(['profit_sharing_level', 'profit_sharing_qualified_at']);
        });
    }
};
