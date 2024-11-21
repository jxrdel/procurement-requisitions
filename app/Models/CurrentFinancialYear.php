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
        // Get the current financial year, e.g., "24/25"
        $currentFinancialYear = CurrentFinancialYear::first()->name;
        $year = substr($currentFinancialYear, 0, 2);
        $nextYear = substr($currentFinancialYear, 3, 2);

        // Base format: "24/25"
        $requisitionYearPart = $year . '/' . $nextYear;

        // Retrieve the last requisition number for the current financial year
        $lastRequisition = Requisition::where('requisition_no', 'like', '%-' . $requisitionYearPart)->latest()->first();

        if ($lastRequisition) {
            // Extract the sequence number from the last requisition number
            $lastRequisitionNo = explode('-', $lastRequisition->requisition_no);
            $sequenceNumber = (int) $lastRequisitionNo[0];

            // Increment the sequence number
            $newSequenceNumber = str_pad($sequenceNumber + 1, 2, '0', STR_PAD_LEFT);
        } else {
            // Start the sequence at 01 if no requisitions exist
            $newSequenceNumber = '01';
        }

        // Combine the sequence number with the financial year
        $requisitionNo = $newSequenceNumber . '-' . $requisitionYearPart;

        return $requisitionNo;
    }
}
