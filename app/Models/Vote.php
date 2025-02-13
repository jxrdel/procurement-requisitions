<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = ['name', 'number', 'is_active'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
