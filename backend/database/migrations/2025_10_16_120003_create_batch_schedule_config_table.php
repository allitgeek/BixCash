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
        Schema::create('batch_schedule_config', function (Blueprint $table) {
            $table->id();

            // Schedule Settings
            $table->boolean('is_enabled')->default(true);
            $table->integer('run_day_of_month')->comment('1-28: which day of month to run');
            $table->time('run_time')->comment('HH:MM:SS format (e.g., 23:00:00)');
            $table->string('timezone', 50)->default('Asia/Karachi');

            // Notifications
            $table->integer('notify_admin_before_hours')->default(24)->comment('Notify X hours before run');
            $table->text('admin_notification_emails')->nullable()->comment('Comma-separated emails');

            // Last Run Info
            $table->timestamp('last_run_at')->nullable();
            $table->timestamp('next_scheduled_run')->nullable();

            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null')->comment('Admin user ID');
            $table->timestamps();
        });

        // Insert default configuration
        DB::table('batch_schedule_config')->insert([
            'is_enabled' => true,
            'run_day_of_month' => 1, // 1st of every month
            'run_time' => '23:00:00', // 11 PM
            'timezone' => 'Asia/Karachi',
            'notify_admin_before_hours' => 24,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batch_schedule_config');
    }
};
