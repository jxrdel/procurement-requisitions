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
            $table->text('hod_note')
                ->nullable()
                ->after('hod_approval');
            $table->text('reporting_officer_note')
                ->nullable()
                ->after('reporting_officer_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropColumn(['hod_note', 'reporting_officer_note']);
        });
    }
};
