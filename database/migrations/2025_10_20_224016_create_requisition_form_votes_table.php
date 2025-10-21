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
        Schema::create('requisition_form_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requisition_request_form_id')
                ->constrained('requisition_request_forms')
                ->onDelete('cascade');
            $table->foreignId('vote_id')
                ->constrained('votes')
                ->onDelete('cascade');
            $table->timestamps();

            // Prevent duplicate votes for the same form
            $table->unique(['requisition_request_form_id', 'vote_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_form_votes');
    }
};
