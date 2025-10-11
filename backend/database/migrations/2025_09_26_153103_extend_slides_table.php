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
        Schema::table('slides', function (Blueprint $table) {
            $table->string('target_url')->nullable()->after('media_path'); // Link destination
            $table->string('button_text')->nullable()->after('target_url'); // CTA button text
            $table->string('button_color')->nullable()->after('button_text'); // Button color
            $table->timestamp('start_date')->nullable()->after('is_active'); // Scheduling
            $table->timestamp('end_date')->nullable()->after('start_date'); // Auto-hide
            $table->foreignId('created_by')->nullable()->after('end_date')->constrained('users')->onDelete('set null');
            $table->json('settings')->nullable()->after('created_by'); // Additional slide settings
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slides', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'target_url', 'button_text', 'button_color', 'start_date',
                'end_date', 'created_by', 'settings'
            ]);
        });
    }
};
