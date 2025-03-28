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
        'is_active',
        'role_id'
    ];

    public function scopeProcurement($query)
    {
        return $query->where('department', 'Procurement')->where('is_active', true);
    }

    public function scopeCostBudgeting($query)
    {
        return $query->where('department', 'Cost & Budgeting')->where('is_active', true);
    }

    public function scopeAccountsPayable($query)
    {
        return $query->where('department', 'Accounts Payable')->where('is_active', true);
    }

    public function scopeVoteControl($query)
    {
        return $query->where('department', 'Vote Control')->where('is_active', true);
    }

    public function scopeAdmin($query)
    {
        return $query->where('department', 'Admin')->where('is_active', true);
    }

    public function scopeCheckStaff($query)
    {
        return $query->where('department', 'Check Staff')->where('is_active', true);
    }

    public function scopeChequeProcessing($query)
    {
        return $query->where('department', 'Cheque Processing')->where('is_active', true);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
