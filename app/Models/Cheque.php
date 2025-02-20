<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    protected $fillable = [
        'date_cheque_processed',
        'cheque_no',
        'date_of_cheque',
        'date_sent_dispatch',
    ];

    public function vendor()
    {
        return $this->belongsTo(RequisitionVendor::class);
    }
}
