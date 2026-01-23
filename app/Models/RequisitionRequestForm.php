<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class RequisitionRequestForm extends Model
{
    protected $fillable = [
        'form_code',
        'requesting_unit',
        'head_of_department_id',
        'contact_person_id',
        'reporting_officer_id',
        'requisition_id',
        'date',
        'category',
        'contact_info',
        'justification',
        'location_of_delivery',
        'date_required_by',
        'estimated_value',
        'availability_of_funds',
        'verified_by_accounts',
        'contact_person_note',
        'date_sent_to_hod',
        'hod_approval_date',
        'hod_approval',
        'hod_digital_signature',
        'reporting_officer_digital_signature',
        'procurement_digital_signature',
        'hod_note',
        'hod_reason_for_denial',
        'reporting_officer_approval_date',
        'reporting_officer_approval',
        'reporting_officer_note',
        'reporting_officer_reason_for_denial',
        'procurement_approval',
        'procurement_approval_date',
        'procurement_reason_for_denial',
        'status',
        'created_by',
        'updated_by',
        'second_reporting_officer_approval',
        'second_reporting_officer_id',
        'third_reporting_officer_approval',
        'third_reporting_officer_id',
        'second_reporting_officer_approval_date',
        'third_reporting_officer_approval_date',
        'forwarding_minute',
        'sent_to_cab',
        'completed_by_cab',
        'sent_to_ps',
        'sent_to_dps',
        'sent_to_cmo',
        'cab_note',
        'date_sent_to_cab',
        'cab_reason_for_denial',
        'virement_required',
    ];

    protected $casts = [
        'availability_of_funds' => 'boolean',
        'verified_by_accounts' => 'boolean',
        'virement_required' => 'boolean',
        'hod_approval' => 'boolean',
        'reporting_officer_approval' => 'boolean',
        'procurement_approval' => 'boolean',
        'procurement_approval_date' => 'datetime',
        'date' => 'date',
        'date_required_by' => 'date',
        'hod_approval_date' => 'datetime',
        'date_sent_to_hod' => 'datetime',
        'reporting_officer_approval_date' => 'datetime',
        'second_reporting_officer_approval_date' => 'datetime',
        'third_reporting_officer_approval_date' => 'datetime',
        'estimated_value' => 'decimal:2',
        'sent_to_cab' => 'boolean',
        'completed_by_cab' => 'boolean',
        'sent_to_ps' => 'boolean',
        'sent_to_dps' => 'boolean',
        'sent_to_cmo' => 'boolean',
        'date_sent_to_cab' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RequisitionRequestForm $form) {

            $financialYear = CurrentFinancialYear::first()->name;

            $yearPrefix = substr($financialYear, 0, 2) . substr($financialYear, 3, 2);

            $maxCode = self::query()
                ->where(DB::raw('SUBSTRING(form_code, 1, 4)'), '=', $yearPrefix)
                ->max('form_code');

            if ($maxCode) {
                $currentSequence = (int) substr($maxCode, 4);
                $newSequence = $currentSequence + 1;
            } else {
                $newSequence = 1;
            }

            $form->form_code = $yearPrefix . str_pad($newSequence, 4, '0', STR_PAD_LEFT);
        });
    }

    public function items()
    {
        return $this->hasMany(FormItem::class);
    }

    public function requestingUnit()
    {
        return $this->belongsTo(Department::class, 'requesting_unit');
    }

    public function headOfDepartment()
    {
        return $this->belongsTo(User::class, 'head_of_department_id');
    }

    public function contactPerson()
    {
        return $this->belongsTo(User::class, 'contact_person_id');
    }

    public function reportingOfficer()
    {
        return $this->belongsTo(User::class, 'reporting_officer_id');
    }

    public function secondReportingOfficer()
    {
        return $this->belongsTo(User::class, 'second_reporting_officer_id');
    }

    public function thirdReportingOfficer()
    {
        return $this->belongsTo(User::class, 'third_reporting_officer_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class, 'requisition_id');
    }

    public function logs()
    {
        return $this->hasMany(FormLog::class, 'requisition_request_form_id');
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(Vote::class, 'requisition_form_votes')
            ->withTimestamps();
    }

    public function uploads()
    {
        return $this->hasMany(FormUpload::class, 'requisition_request_form_id');
    }
}
