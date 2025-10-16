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
            $table->string('contact_person_name')->nullable()->after('business_name');
            $table->timestamp('registration_date')->nullable()->after('approved_by');
            $table->text('approval_notes')->nullable()->after('registration_date');
            $table->text('rejection_notes')->nullable()->after('approval_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partner_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'contact_person_name',
                'registration_date',
                'approval_notes',
                'rejection_notes'
            ]);
        });
    }
};
