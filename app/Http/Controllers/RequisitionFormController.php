<?php

namespace App\Http\Controllers;

use App\Models\RequisitionRequestForm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RequisitionFormController extends Controller
{
    public function index()
    {
        return view('requisitions.forms.index');
    }

    public function getForms()
    {
        $forms = RequisitionRequestForm::with('contactPerson')->get();

        return DataTables::of($forms)
            ->addColumn('date_created_formatted', function ($row) {
                return Carbon::parse($row->created_at)->format('d/m/Y');
            })
            ->addColumn('contact_person_name', function ($row) {
                return $row->contactPerson->name ?? 'N/A';
            })
            ->addColumn('status_badge', function ($row) {
                $status = $row->status ?? 'Draft';
                $bgColor = $status === 'Completed' ? '#28a745' : '#e09e03';
                return '<div style="text-align:center;"><span style="background-color: ' . $bgColor . ' !important;" class="badge" style="color: white;">' . $status . '</span></div>';
            })
            ->addColumn('actions', function ($row) {
                $viewUrl = route('requisition_forms.view', $row->id);
                return '<div style="text-align:center"><a href="' . $viewUrl . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }
}
