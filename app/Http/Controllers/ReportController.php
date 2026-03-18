<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionRequestForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function requisitions()
    {
        $totalForms = RequisitionRequestForm::count();
        $totalRequisitions = Requisition::count();
        $sentToCb = Requisition::where('sent_to_cb', true)->count();
        
        $statuses = Requisition::select('requisition_status', DB::raw('count(*) as total'))
            ->groupBy('requisition_status')
            ->get();

        $pdf = Pdf::loadView('reports.requisitions', compact('totalForms', 'totalRequisitions', 'sentToCb', 'statuses'));
        
        return $pdf->stream('requisition-report.pdf');
    }
}
