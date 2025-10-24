<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormUpload extends Model
{
    protected $fillable = ['file_name', 'file_path', 'uploaded_by', 'requisition_request_form_id'];

    public function form()
    {
        return $this->belongsTo(RequisitionRequestForm::class);
    }
}
