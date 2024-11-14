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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->string('requisition_no')->unique();
            $table->unsignedBigInteger('requesting_unit');
            $table->string('file_no')->nullable();
            $table->string('item')->nullable();
            $table->string('source_of_funds')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->date('date_assigned')->nullable();
            $table->date('date_sent_dps')->nullable();
            $table->string('ps_approval');
            $table->date('ps_approval_date')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('amount')->nullable();
            $table->string('denied_note')->nullable();
            $table->boolean('sent_to_cb')->default(false);

            //Cost & Budgeting
            $table->dateTime('date_sent_cb')->nullable();
            $table->date('date_sent_request_mof')->nullable();
            $table->string('request_no')->nullable();
            $table->string('release_no')->nullable();
            $table->date('release_date')->nullable();
            $table->string('change_of_vote_no')->nullable();
            $table->boolean('is_completed_cb')->default(false); //Becomes true when cost budgeting part is completed

            //Procurement
            $table->string('purchase_order_no')->nullable();
            $table->date('purchase_order_date')->nullable();
            $table->date('eta')->nullable();
            $table->date('date_sent_commit')->nullable();
            $table->string('invoice_no')->nullable();
            $table->date('date_invoice_received')->nullable();
            $table->dateTime('date_sent_ap')->nullable();

            //Vote Control
            $table->string('batch_no')->nullable();
            $table->string('voucher_no')->nullable();
            $table->date('vc_commitment_date')->nullable();

            //Check Room
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

            $table->boolean('is_completed')->default(false);
            $table->dateTime('date_completed')->nullable();
            $table->string('requisition_status')->default('At Procurement');

            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
            $table->foreign('requesting_unit')->references('id')->on('departments')->onDelete('cascade');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};
