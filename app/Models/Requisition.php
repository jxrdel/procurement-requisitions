<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'requisition_no',
        'requesting_unit',
        'file_no',
        'item',
        'source_of_funds',
        'assigned_to',
        'date_assigned',
        'date_sent_dps',
        'ps_approval',
        'ps_approval_date',
        'vendor_name',
        'amount',
        'denied_note',
        'sent_to_cb',
        'date_sent_cb',
        'date_sent_request_mof',
        'request_no',
        'release_no',
        'release_date',
        'change_of_vote_no',
        'is_completed_cb',
        'purchase_order_no',
        'purchase_order_date',
        'eta',
        'date_sent_commit',
        'invoice_no',
        'date_invoice_received',
        'date_sent_ap',
        'batch_no',
        'voucher_no',
        'vc_commitment_date',
        'date_received_from_vc',
        'voucher_destination',
        'date_sent_audit',
        'date_received_from_audit',
        'date_sent_chequeprocessing',
        'date_of_cheque',
        'cheque_no',
        'date_cheque_processed',
        'date_sent_dispatch',
        'is_completed',
        'date_completed',
        'requisition_status',
        'created_by',
        'updated_by',
    ];


    public function statuslogs()
    {
        return $this->hasMany(Status::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'requesting_unit');
    }

    public function procurement_officer()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function cost_budgeting_requisition()
    {
        return $this->hasOne(CBRequisition::class);
    }

    public function vote_control_requisition()
    {
        return $this->hasOne(VoteControlRequisition::class);
    }

    public function check_room_requisition()
    {
        return $this->hasOne(CheckRoomRequisition::class);
    }

    public function cheque_processing_requisition()
    {
        return $this->hasOne(ChequeProcessingRequisition::class);
    }

    public function file_uploads()
    {
        return $this->hasMany(FileUpload::class);
    }

    //Get unique requisition number
    public static function generateRequisitionNo()
    {
        //Get amount of requisitions for the year so far
        $requisitions = Requisition::whereYear('created_at', date('Y'))->count();

        //Increment by 1
        $requisitions += 1;

        //Format requisition number
        $requisition_no = 'REQ/' . date('Y') . '/' . str_pad($requisitions, 4, '0', STR_PAD_LEFT);
        return $requisition_no;
    }
}
