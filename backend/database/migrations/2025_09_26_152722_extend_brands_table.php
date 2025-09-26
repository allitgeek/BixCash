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
        Schema::table('brands', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('logo_path')->constrained()->onDelete('set null');
            $table->foreignId('partner_id')->nullable()->after('category_id')->constrained('users')->onDelete('set null');
            $table->text('description')->nullable()->after('partner_id');
            $table->string('website')->nullable()->after('description');
            $table->decimal('commission_rate', 5, 2)->default(0.00)->after('website');
            $table->boolean('is_featured')->default(false)->after('commission_rate');
            $table->boolean('is_active')->default(true)->after('is_featured');
            $table->enum('status', ['active', 'inactive', 'pending_approval'])->default('active')->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['partner_id']);
            $table->dropColumn([
                'category_id', 'partner_id', 'description', 'website',
                'commission_rate', 'is_featured', 'is_active', 'status'
            ]);
        });
    }
};
