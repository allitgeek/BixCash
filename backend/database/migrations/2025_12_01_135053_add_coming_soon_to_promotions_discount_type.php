<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add 'coming_soon' option
        DB::statement("ALTER TABLE promotions MODIFY COLUMN discount_type ENUM('upto', 'flat', 'coming_soon') DEFAULT 'upto'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (will fail if any rows have 'coming_soon')
        DB::statement("ALTER TABLE promotions MODIFY COLUMN discount_type ENUM('upto', 'flat') DEFAULT 'upto'");
    }
};
