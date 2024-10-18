<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    protected $fillable = [
        'requuisition_status',
        'requisition_no',
        'requesting_unit',
        'file_number',
        'item',
        'source_of_funds',
        'assigned_to',
        'date_sent_ps',
        'ps_approval',
        'ps_approval_date',
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
}
