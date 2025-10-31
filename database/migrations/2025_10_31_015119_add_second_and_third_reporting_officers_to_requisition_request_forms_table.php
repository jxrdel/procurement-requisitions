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
            $table->boolean('second_reporting_officer_approval')->nullable()->default(null);
            $table->foreignId('second_reporting_officer_id')->nullable()->constrained('users');
            $table->boolean('third_reporting_officer_approval')->nullable()->default(null);
            $table->foreignId('third_reporting_officer_id')->nullable()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropForeign(['second_reporting_officer_id']);
            $table->dropColumn('second_reporting_officer_id');
            $table->dropColumn('second_reporting_officer_approval');
            $table->dropForeign(['third_reporting_officer_id']);
            $table->dropColumn('third_reporting_officer_id');
            $table->dropColumn('third_reporting_officer_approval');
        });
    }
};