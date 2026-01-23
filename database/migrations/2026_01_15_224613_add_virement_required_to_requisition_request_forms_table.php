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
            $table->boolean('virement_required')->default(false)->after('verified_by_accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisition_request_forms', function (Blueprint $table) {
            $table->dropColumn('virement_required');
        });
    }
};
