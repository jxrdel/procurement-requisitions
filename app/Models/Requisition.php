<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'requisition_status',
        'requisition_no',
        'requesting_unit',
        'file_number',
        'item',
        'source_of_funds',
        'assigned_to',
        'date_sent_ps',
        'ps_approval',
        'ps_approval_date',
        'sent_to_dfa',
        'date_sent_dfa',
        'date_sent_request_mof',
        'request_no',
        'release_no',
        'release_date',
        'change_of_vote_no',
        'purchase_order_no',
        'eta',
        'date_sent_commit',
        'invoice_no',
        'date_invoice_received',
        'date_sent_ap',
        'date_sent_chequeroom',
        'date_of_cheque',
        'cheque_no',
        'date_cheque_forwarded',
        'is_completed',
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

    public function accounts_requisition()
    {
        return $this->hasOne(AccountsRequisition::class);
    }

    public function file_uploads()
    {
        return $this->hasMany(FileUpload::class);
    }
}
