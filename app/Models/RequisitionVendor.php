<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequisitionVendor extends Model
{
    protected $table = 'requisition_vendors';

    protected $fillable = [
        'vendor_name',
        'amount',

        //Procurement
        'vendor_status',
        'purchase_order_no',
        'eta',
        'date_sent_commit',
        'invoice_no',
        'date_invoice_received',
        'date_sent_ap',
        'sent_to_ap',

        // Cost & Budgeting
        'date_sent_request_mof',
        'release_type',
        'request_category',
        'request_no',
        'release_no',
        'release_date',
        'change_of_vote_no',

        // AP
        'date_received_ap',
        'date_sent_vc',

        // Vote Control
        'batch_no',
        'voucher_no',
        'date_sent_checkstaff',

        // Check Staff
        'date_received_from_vc',
        'voucher_destination',
        'date_sent_audit',
        'date_received_from_audit',
        'date_sent_chequeprocessing',

        // Cheque Processing
        'date_of_cheque',
        'cheque_no',
        'date_cheque_processed',
        'date_sent_dispatch',

        'is_completed',
        'date_completed',

        'requisition_id', // Foreign key
    ];


    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function invoices()
    {
        return $this->hasMany(VendorInvoice::class, 'vendor_id');
    }

    public function ap()
    {
        return $this->hasOne(APVendor::class, 'vendor_id');
    }

    public function voteControl()
    {
        return $this->hasOne(VoteControlVendor::class, 'vendor_id');
    }

    public function checkStaff()
    {
        return $this->hasOne(CheckStaffVendor::class, 'vendor_id');
    }

    public function chequeProcessing()
    {
        return $this->hasOne(ChequeProcessingVendor::class, 'vendor_id');
    }

    public function cheques()
    {
        return $this->hasMany(Cheque::class, 'vendor_id');
    }
}
