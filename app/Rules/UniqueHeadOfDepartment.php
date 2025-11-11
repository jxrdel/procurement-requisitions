<?php

namespace App\Rules;

use App\Models\Department;
use Illuminate\Contracts\Validation\Rule;

class UniqueHeadOfDepartment implements Rule
{
    protected $departmentId;

    public function __construct($departmentId)
    {
        $this->departmentId = $departmentId;
    }

    public function passes($attribute, $value)
    {
        if (empty($value)) {
            return true;
        }

        $query = Department::where('head_of_department_id', $value);

        if ($this->departmentId) {
            $query->where('id', '!=', $this->departmentId);
        }

        return $query->count() === 0;
    }

    public function message()
    {
        return 'This user is already the head of another department.';
    }
}