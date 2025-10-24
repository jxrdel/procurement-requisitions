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
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->text('hod_digital_signature')->nullable()->after('hod_approval');
            $table->text('reporting_officer_digital_signature')->nullable()->after('reporting_officer_approval');
            $table->text('procurement_digital_signature')->nullable()->after('procurement_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropColumn([
                'hod_digital_signature',
                'reporting_officer_digital_signature',
                'procurement_digital_signature',
            ]);
        });
    }
};
