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
        Schema::create('check_room_requisitions', function (Blueprint $table) {
            $table->id();
            $table->date('date_received');
            $table->dateTime('date_completed')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->unsignedBigInteger('requisition_id');

            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->unique('requisition_id'); //Ensures that a requisition can only have one cost budgeting requisition
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_room_requisitions');
    }
};
