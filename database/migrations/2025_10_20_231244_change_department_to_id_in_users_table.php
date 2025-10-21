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
        Schema::table('users', function (Blueprint $table) {
            // Drop the old department column
            $table->dropColumn('department');

            // Add the new department_id column with foreign key
            $table->foreignId('department_id')
                ->nullable()
                ->after('email')
                ->constrained('departments')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key and column
            $table->dropForeign(['department_id']);
            $table->dropColumn('department_id');

            // Restore the old department column
            $table->string('department')->after('email');
        });
    }
};
