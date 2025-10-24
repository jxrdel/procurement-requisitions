<?php

namespace App\Http\Controllers;

use App\Models\RequisitionRequestForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;

class RequisitionFormController extends Controller
{
    public function index()
    {
        return view('requisitions.forms.index');
    }

    public function getForms()
    {
        $forms = RequisitionRequestForm::with(['items', 'requisition'])->select('requisition_request_forms.*');

        return DataTables::of($forms)
            ->addColumn('date_created_formatted', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('items_list', function ($row) {
                return $row->items->pluck('name')->implode(', ') ?: 'N/A';
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status ?? 'Draft';
                $bgColor = $status === 'Completed' ? '#28a745' : '#e09e03';
                return '<div style="text-align:center;"><span style="background-color: ' . $bgColor . ' !important; color: white;" class="badge">' . $status . '</span></div>';
            })
            ->addColumn('requisition_status_badge', function ($row) {
                if ($row->requisition) {
                    $status = $row->requisition->requisition_status;
                    $bgColor = '#e09e03';
                    $textColor = 'white';
                } else {
                    $status = 'N/A';
                    $bgColor = '#6c757d';
                    $textColor = 'white';
                }
                return '<div style="text-align:center;"><span style="background-color: ' . $bgColor . ' !important; color: ' . $textColor . ';" class="badge">' . $status . '</span></div>';
            })
            ->filterColumn('items_list', function (Builder $query, $keyword) {
                $query->whereHas('items', function (Builder $q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })
            ->addColumn('actions', function ($row) {
                $viewUrl = route('requisition_forms.view', $row->id);
                return '<div style="text-align:center"><a href="' . $viewUrl . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->rawColumns(['status_badge', 'requisition_status_badge', 'actions'])
            ->make(true);
    }

    public function preview($id)
    {
        $requisitionForm = RequisitionRequestForm::with([
            'requestingUnit',
            'headOfDepartment',
            'contactPerson',
            'items',
            'votes',
            'reportingOfficer'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('PDF.requisition-form', compact('requisitionForm'))
            ->setPaper('legal', 'portrait');

        return $pdf->stream('requisition-form-' . $requisitionForm->form_code . '.pdf');
    }
}
