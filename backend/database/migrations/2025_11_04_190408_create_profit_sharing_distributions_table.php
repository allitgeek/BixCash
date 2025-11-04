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
        Schema::create('profit_sharing_distributions', function (Blueprint $table) {
            $table->id();
            $table->string('distribution_month'); // YYYY-MM format
            $table->decimal('total_amount', 15, 2);
            $table->enum('status', ['pending', 'dispersed', 'cancelled'])->default('pending');

            // Level 1
            $table->decimal('level_1_amount', 15, 2)->default(0);
            $table->decimal('level_1_per_customer', 15, 2)->default(0);
            $table->decimal('level_1_percentage', 5, 2)->default(0);
            $table->integer('level_1_recipients')->default(0);

            // Level 2
            $table->decimal('level_2_amount', 15, 2)->default(0);
            $table->decimal('level_2_per_customer', 15, 2)->default(0);
            $table->decimal('level_2_percentage', 5, 2)->default(0);
            $table->integer('level_2_recipients')->default(0);

            // Level 3
            $table->decimal('level_3_amount', 15, 2)->default(0);
            $table->decimal('level_3_per_customer', 15, 2)->default(0);
            $table->decimal('level_3_percentage', 5, 2)->default(0);
            $table->integer('level_3_recipients')->default(0);

            // Level 4
            $table->decimal('level_4_amount', 15, 2)->default(0);
            $table->decimal('level_4_per_customer', 15, 2)->default(0);
            $table->decimal('level_4_percentage', 5, 2)->default(0);
            $table->integer('level_4_recipients')->default(0);

            // Level 5
            $table->decimal('level_5_amount', 15, 2)->default(0);
            $table->decimal('level_5_per_customer', 15, 2)->default(0);
            $table->decimal('level_5_percentage', 5, 2)->default(0);
            $table->integer('level_5_recipients')->default(0);

            // Level 6
            $table->decimal('level_6_amount', 15, 2)->default(0);
            $table->decimal('level_6_per_customer', 15, 2)->default(0);
            $table->decimal('level_6_percentage', 5, 2)->default(0);
            $table->integer('level_6_recipients')->default(0);

            // Level 7
            $table->decimal('level_7_amount', 15, 2)->default(0);
            $table->decimal('level_7_per_customer', 15, 2)->default(0);
            $table->decimal('level_7_percentage', 5, 2)->default(0);
            $table->integer('level_7_recipients')->default(0);

            // Meta fields
            $table->integer('total_recipients')->default(0);
            $table->foreignId('created_by_admin_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('dispersed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('distribution_month');
            $table->index('status');
            $table->index('created_by_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_sharing_distributions');
    }
};
