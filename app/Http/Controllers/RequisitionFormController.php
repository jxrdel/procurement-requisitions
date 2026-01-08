<?php

namespace App\Http\Controllers;

use App\Models\RequisitionRequestForm;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RequisitionFormController extends Controller
{
    public function index()
    {
        return view('requisitions.forms.index');
    }

    public function getForms()
    {
        $user = Auth::user();
        $forms = RequisitionRequestForm::with(['items', 'requisition'])->select('requisition_request_forms.*');

        if ($user->role->name !== 'Super Admin') {
            $forms->where('requesting_unit', $user->department_id);
        }

        return DataTables::of($forms)
            ->addColumn('date_created_formatted', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('items_list', function ($row) {
                $items = $row->items->pluck('name')->implode(', ');
                return strlen($items) > 25 ? substr($items, 0, 25) . '...' : $items;
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status ?? 'Draft';
                $bgColor = $status === 'Completed' ? '#8bc34a' : '#e09e03';
                return '<div style="text-align:center;"><span style="background-color: ' . $bgColor . ' !important; color: white;" class="badge">' . $status . '</span></div>';
            })
            ->addColumn('requisition_status_badge', function ($row) {
                if ($row->requisition) {
                    $status = $row->requisition->requisition_status;
                    $bgColor = $status === 'Completed' ? '#8bc34a' : '#e09e03';
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
            ->filterColumn('requisition.status', function (Builder $query, $keyword) {
                $query->whereHas('requisition', function (Builder $q) use ($keyword) {
                    $q->where('requisition_status', 'like', "%{$keyword}%");
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
            'reportingOfficer',
            'secondReportingOfficer',
            'thirdReportingOfficer'
        ])->findOrFail($id);

        $officersByRole = [
            'Permanent Secretary' => null,
            'Deputy Permanent Secretary' => null,
            'Chief Medical Officer' => null,
        ];
        $datesByRole = [
            'Permanent Secretary' => null,
            'Deputy Permanent Secretary' => null,
            'Chief Medical Officer' => null,
        ];

        $allOfficers = [
            ['officer' => $requisitionForm->reportingOfficer, 'approval' => $requisitionForm->reporting_officer_approval, 'date' => $requisitionForm->reporting_officer_approval_date],
            ['officer' => $requisitionForm->secondReportingOfficer, 'approval' => $requisitionForm->second_reporting_officer_approval, 'date' => $requisitionForm->second_reporting_officer_approval_date],
            ['officer' => $requisitionForm->thirdReportingOfficer, 'approval' => $requisitionForm->third_reporting_officer_approval, 'date' => $requisitionForm->third_reporting_officer_approval_date],
        ];

        foreach ($allOfficers as $data) {
            if ($data['approval'] && $data['officer']) {
                $role = $data['officer']->reporting_officer_role;
                if (array_key_exists($role, $officersByRole) && $officersByRole[$role] === null) {
                    $officersByRole[$role] = $data['officer'];
                    $datesByRole[$role] = $data['date'];
                }
            }
        }

        $pdf = Pdf::loadView('PDF.requisition-form', compact('requisitionForm', 'officersByRole', 'datesByRole'))->setPaper('legal', 'portrait');

        return $pdf->stream('requisition-form-' . $requisitionForm->form_code . '.pdf');
    }
}
