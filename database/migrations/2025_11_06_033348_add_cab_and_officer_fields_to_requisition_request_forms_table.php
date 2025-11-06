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
            $table->boolean('sent_to_cab')->default(false)->after('forwarding_minute');
            $table->boolean('completed_by_cab')->default(false)->after('sent_to_cab');
            $table->boolean('sent_to_ps')->default(false)->after('completed_by_cab');
            $table->boolean('sent_to_dps')->default(false)->after('sent_to_ps');
            $table->boolean('sent_to_cmo')->default(false)->after('sent_to_dps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropColumn(['sent_to_cab', 'completed_by_cab', 'sent_to_ps', 'sent_to_dps', 'sent_to_cmo']);
        });
    }
};
