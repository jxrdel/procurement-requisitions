<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorInvoice extends Model
{
    protected $fillable = [
        'invoice_no',
        'invoice_amount',
        'date_invoice_received',
        'date_sent_commit',
        'date_sent_ap',
    ];

    public function vendor()
    {
        return $this->belongsTo(RequisitionVendor::class);
    }
}
