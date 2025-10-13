<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormItem extends Model
{
    protected $fillable = [
        'requisition_request_form_id',
        'name',
        'qty_in_stock',
        'qty_requesting',
        'unit_of_measure',
        'size',
        'colour',
        'brand_model',
        'other',
    ];

    public function form()
    {
        return $this->belongsTo(RequisitionRequestForm::class);
    }
}
