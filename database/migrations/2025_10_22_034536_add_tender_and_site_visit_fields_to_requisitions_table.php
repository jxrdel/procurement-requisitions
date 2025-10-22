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
        Schema::table('requisitions', function (Blueprint $table) {
            $table->boolean('site_visit')->default(false)->after('date_received_procurement');
            $table->date('site_visit_date')->nullable()->after('site_visit');
            $table->date('tender_issue_date')->nullable()->after('site_visit_date');
            $table->date('tender_deadline_date')->nullable()->after('tender_issue_date');
            $table->date('evaluation_start_date')->nullable()->after('tender_deadline_date');
            $table->date('evaluation_end_date')->nullable()->after('evaluation_start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn([
                'site_visit',
                'site_visit_date',
                'tender_issue_date',
                'tender_deadline_date',
                'evaluation_start_date',
                'evaluation_end_date',
            ]);
        });
    }
};
