<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'date_cheque_processed',
        'cheque_no',
        'cheque_amount',
        'date_of_cheque',
        'date_sent_dispatch',
        'invoice_no'
    ];

    public function vendor()
    {
        return $this->belongsTo(RequisitionVendor::class);
    }
}
