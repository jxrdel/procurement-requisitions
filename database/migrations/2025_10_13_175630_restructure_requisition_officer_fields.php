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
            $table->dropForeign(['approvalOfficer']);
        });

        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->renameColumn('approvalOfficer', 'reporting_officer_id');
            $table->renameColumn('approval_officer_approval', 'reporting_officer_approval');
            $table->renameColumn('approval_officer_approval_date', 'reporting_officer_approval_date');
        });

        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dateTime('date_sent_to_hod')->nullable()->after('verified_by_accounts');
            $table->text('hod_reason_for_denial')->nullable()->after('hod_approval');
            $table->text('reporting_officer_reason_for_denial')->nullable()->after('reporting_officer_approval');
            $table->foreign('reporting_officer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropForeign(['reporting_officer_id']);
        });

        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropColumn(['date_sent_to_hod', 'hod_reason_for_denial', 'reporting_officer_reason_for_denial']);

            $table->renameColumn('reporting_officer_id', 'approvalOfficer');
            $table->renameColumn('reporting_officer_approval', 'approval_officer_approval');
            $table->renameColumn('reporting_officer_approval_date', 'approval_officer_approval_date');
        });

        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->foreign('approvalOfficer')->references('id')->on('users')->onDelete('set null');
        });
    }
};
