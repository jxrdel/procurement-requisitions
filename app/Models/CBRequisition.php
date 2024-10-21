<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CBRequisition extends Model
{
    protected $table = 'cost_budgeting_requisitions';

    protected $fillable = [
        'date_received',
        'date_completed',
        'is_completed',
        'requisition_id',
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
