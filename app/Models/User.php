<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
        'is_active',
        'role_id',
        'is_reporting_officer',
        'reporting_officer_role',
    ];

    protected $casts = [
        'is_reporting_officer' => 'boolean',
    ];

    public function scopeProcurement($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Procurement Unit');
        })->where('is_active', true);
    }

    public function scopeCostBudgeting($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Cost & Budgeting');
        })->where('is_active', true);
    }

    public function scopeAccountsPayable($query)
    {
        // Assuming 'Accounts Payable' is the correct department name used in the database.
        // If the department name is 'Accounts', you should change 'Accounts Payable' below.
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Accounts Payable');
        })->where('is_active', true);
    }

    public function scopeVoteControl($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Vote Control');
        })->where('is_active', true);
    }

    public function scopeAdmin($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'General Administration'); // Assumes 'Admin' maps to a full department name
        })->where('is_active', true);
    }

    public function scopeCheckStaff($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Check Dispatch'); // Assumes 'Check Staff' maps to 'Check Dispatch'
        })->where('is_active', true);
    }

    public function scopeChequeProcessing($query)
    {
        return $query->whereHas('department', function ($q) {
            $q->where('name', 'Cheque Processing');
        })->where('is_active', true);
    }

    public function scopeReportingOfficers($query)
    {
        $query->where('is_reporting_officer', true);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function getInitialsAttribute()
    {
        $names = explode(' ', $this->name);
        $initials = '';

        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1)) . '.';
        }

        return rtrim($initials, '.');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getDigitalSignatureAttribute(): string
    {
        $salt = config('app.key');

        return hash('sha256', $this->username . $salt);
    }
}
