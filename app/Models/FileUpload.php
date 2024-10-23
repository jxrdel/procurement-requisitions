<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    protected $fillable = ['file_name', 'file_path', 'requisition_id'];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
