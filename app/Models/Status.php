<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'requisition_statuses';

    protected $fillable = [
        'details',
        // 'date',
        'created_by',
        'updated_by',
        'requisition_id',
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'username');
    }
}
