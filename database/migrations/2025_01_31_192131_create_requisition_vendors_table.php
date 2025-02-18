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
        Schema::create('requisition_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->decimal('amount', 20, 2);
            $table->string('vendor_status')->nullable();

            //Procurement
            $table->string('purchase_order_no')->nullable();
            $table->date('eta')->nullable();
            $table->date('date_sent_commit')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('date_invoice_received')->nullable();
            $table->date('date_sent_ap')->nullable();
            $table->boolean('sent_to_ap')->default(false);

            //Cost & Budgeting
            $table->date('date_sent_request_mof')->nullable();
            $table->string('release_type')->nullable();
            $table->string('request_category')->nullable();
            $table->string('request_no')->nullable();
            $table->string('release_no')->nullable();
            $table->date('release_date')->nullable();
            $table->string('change_of_vote_no')->nullable();

            //AP
            $table->date('date_received_ap')->nullable();
            $table->date('date_sent_vc')->nullable();

            //Vote Control
            $table->string('batch_no')->nullable();
            $table->string('voucher_no')->nullable();
            $table->date('date_sent_checkstaff')->nullable();

            //Check Staff
            $table->date('date_received_from_vc')->nullable();
            $table->string('voucher_destination')->nullable();
            $table->date('date_sent_audit')->nullable();
            $table->date('date_received_from_audit')->nullable();
            $table->date('date_sent_chequeprocessing')->nullable();

            //Cheque Processing
            $table->date('date_of_cheque')->nullable();
            $table->string('cheque_no')->nullable();
            $table->date('date_cheque_processed')->nullable();
            $table->date('date_sent_dispatch')->nullable();

            $table->unsignedBigInteger('requisition_id');


            $table->foreign('requisition_id')->references('id')->on('requisitions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisition_vendors');
    }
};
