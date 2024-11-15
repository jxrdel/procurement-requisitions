<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrentFinancialYear extends Model
{
    protected $table = 'current_financial_year';
    protected $fillable = ['name'];

    // Generate requisition number based on the current financial year
    public static function generateRequisitionNo()
    {
        $currentFinancialYear = CurrentFinancialYear::first()->name;
        $year = substr($currentFinancialYear, 0, 2);
        $nextYear = substr($currentFinancialYear, 3, 2);

        $requisitionNo = $year . '/' . $nextYear . '/';

        $lastRequisition = Requisition::where('requisition_no', 'like', '%' . $requisitionNo . '%')->latest()->first();

        if ($lastRequisition) {
            $lastRequisitionNo = $lastRequisition->requisition_no;
            $lastRequisitionNo = explode('/', $lastRequisitionNo);
            $lastRequisitionNo = end($lastRequisitionNo);

            $requisitionNo .= str_pad((int)$lastRequisitionNo + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $requisitionNo .= '001';
        }

        return $requisitionNo;
    }
}
