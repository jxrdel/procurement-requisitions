<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormLog extends Model
{
    protected $fillable = [
        'details',
        'created_by',
    ];
}
