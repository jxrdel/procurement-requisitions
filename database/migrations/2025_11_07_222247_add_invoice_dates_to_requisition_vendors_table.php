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
        Schema::table('requisition_vendors', function (Blueprint $table) {
            $table->date('date_received_ap_invoices')->nullable()->after('date_sent_vc');
            $table->date('date_sent_vc_invoices')->nullable()->after('date_received_ap_invoices');
            $table->date('date_committed_vc')->nullable()->after('date_sent_vc_invoices');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_vendors', function (Blueprint $table) {
            $table->dropColumn(['date_received_ap_invoices', 'date_sent_vc_invoices', 'date_committed_vc']);
        });
    }
};
