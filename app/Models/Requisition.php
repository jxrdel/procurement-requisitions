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
        'date_received_procurement',
        'actual_cost',
        'funding_availability',
        'date_sent_aov_procurement',
        'note_to_ps',
        'note_to_ps_date',
        'site_visit',
        'site_visit_date',
        'tender_type',
        'is_first_pass',
        'tender_issue_date',
        'tender_deadline_date',
        'evaluation_start_date',
        'evaluation_end_date',
        'date_sent_dps',
        'ps_approval',
        'ps_approval_date',
        'vendor_name',
        'amount',
        'denied_note',
        'sent_to_cb',
        'date_sent_cb',
        'sent_to_ap_first_pass',
        'release_type',
        'request_category',
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
        'date_received_ap',
        'date_sent_vc',
        'batch_no',
        'voucher_no',
        'vc_commitment_date',
        'date_sent_checkstaff',
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

    protected $casts = [
        'site_visit' => 'boolean',
        'note_to_ps' => 'boolean',
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

    public function ap_requisition()
    {
        return $this->hasOne(APRequisition::class);
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

    public function vendors()
    {
        return $this->hasMany(RequisitionVendor::class);
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

    //Check if all vendor status are completed
    public function isCompleted()
    {
        $vendors = $this->vendors;

        foreach ($vendors as $vendor) {
            if (!$vendor->is_completed) {
                return false;
            }
        }

        return true;
    }

    //Get requisition form
    public function requisitionForm()
    {
        return $this->hasOne(RequisitionRequestForm::class, 'requisition_id');
    }
}
