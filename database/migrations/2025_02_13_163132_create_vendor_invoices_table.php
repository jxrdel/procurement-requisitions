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
        Schema::create('vendor_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->decimal('invoice_amount', 20, 2);
            $table->date('date_invoice_received')->nullable();
            $table->date('date_sent_commit')->nullable();
            $table->date('date_sent_ap')->nullable();

            $table->unsignedBigInteger('vendor_id');
            $table->foreign('vendor_id')->references('id')->on('requisition_vendors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_invoices');
    }
};
