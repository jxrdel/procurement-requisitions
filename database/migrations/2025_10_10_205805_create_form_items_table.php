<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('form_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('requisition_request_form_id')->nullable();

            $table->string('name');
            $table->integer('qty_in_stock')->default(0);
            $table->integer('qty_requesting')->default(1);
            $table->string('unit_of_measure')->nullable();
            $table->string('size')->nullable();
            $table->string('colour')->nullable();
            $table->string('brand_model')->nullable();
            $table->string('other')->nullable();

            $table->foreign('requisition_request_form_id')
                ->references('id')
                ->on('requisition_request_forms')
                ->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('form_items');
    }
};
