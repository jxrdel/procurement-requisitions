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
        Schema::create('vote_control_vendors', function (Blueprint $table) {
            $table->id();
            $table->date('date_received');
            $table->dateTime('date_completed')->nullable();
            $table->boolean('is_completed')->default(false);
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
        Schema::dropIfExists('vote_control_vendors');
    }
};
