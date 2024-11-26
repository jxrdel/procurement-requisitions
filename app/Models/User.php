<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'department',
        'role_id'
    ];

    public function scopeProcurement($query)
    {
        return $query->where('department', 'Procurement');
    }

    public function scopeCostBudgeting($query)
    {
        return $query->where('department', 'Cost & Budgeting');
    }

    public function scopeVoteControl($query)
    {
        return $query->where('department', 'Vote Control');
    }

    public function scopeAdmin($query)
    {
        return $query->where('department', 'Admin');
    }

    public function scopeCheckStaff($query)
    {
        return $query->where('department', 'Check Staff');
    }

    public function scopeChequeProcessing($query)
    {
        return $query->where('department', 'Cheque Processing');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
