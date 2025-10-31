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
            $table->decimal('actual_cost', 20, 2)->nullable()->after('date_assigned'); // or after whichever field fits best
            $table->string('funding_availability')->nullable()->after('actual_cost'); // stores vote number or code
            $table->date('date_sent_aov_procurement')->nullable()->after('funding_availability');
            $table->boolean('note_to_ps')->default(false)->after('date_sent_aov_procurement');
            $table->date('note_to_ps_date')->nullable()->after('note_to_ps');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('requisitions', function (Blueprint $table) {
            $table->dropColumn([
                'actual_cost',
                'funding_availability',
                'date_sent_aov_procurement',
                'note_to_ps',
                'note_to_ps_date',
            ]);
        });
    }
};
