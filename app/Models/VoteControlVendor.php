<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteControlVendor extends Model
{
    protected $fillable = [
        'date_received',
        'date_completed',
        'is_completed',
        'vendor_id',
    ];

    public function vendor()
    {
        return $this->belongsTo(RequisitionVendor::class, 'vendor_id');
    }
}
