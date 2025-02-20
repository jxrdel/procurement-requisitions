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
        Schema::create('cheques', function (Blueprint $table) {
            $table->id();
            $table->date('date_cheque_processed')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('date_of_cheque')->nullable();
            $table->date('date_sent_dispatch')->nullable();
            $table->unsignedBigInteger('vendor_id');

            $table->foreign('vendor_id')->references('id')->on('requisition_vendors')->onDelete('cascade');
            $table->unique('vendor_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cheques');
    }
};
