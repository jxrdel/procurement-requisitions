<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class RequisitionController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-requisitions-index')) {
            abort(403);
        }
        return view('requisitions.index');
    }
    public function getRequisitions()
    {
        $requisitions = Requisition::leftJoin('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->leftJoin('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->leftJoin('requisition_vendors', 'requisitions.id', '=', 'requisition_vendors.requisition_id')
            ->select([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.item',
                'users.name as EmployeeName',
                'departments.name as RequestingUnit',
                'requisitions.requisition_status'
            ])
            ->selectRaw("
                CAST(cost_budgeting_requisitions.is_completed AS INT) AS is_completed,
                STRING_AGG(requisition_vendors.vendor_status, ', ') AS vendor_status, 
                COUNT(requisition_vendors.id) AS vendor_count,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->groupBy([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.item',
                'users.name',
                'departments.name',
                'requisitions.requisition_status',
                'cost_budgeting_requisitions.is_completed'
            ]);

        // Restrict for Viewers (not Procurement or Office of the Permanent Secretary)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department->name !== 'Procurement Unit' && Auth::user()->department->name !== 'Office of the Permanent Secretary' && Auth::user()->department->name !== 'Office of the Deputy Permanent Secretary') {
            $requisitions->where('departments.name', Auth::user()->department->name);
        }

        return DataTables::of($requisitions)
            ->editColumn('vendor_status', function ($row) {
                // If cost budgeting requisition is NOT completed, return requisition_status
                if (!$row->is_completed) {
                    return $row->requisition_status ?? "No Status";
                }

                // Convert vendor statuses into an array
                $vendorStatuses = explode(', ', $row->vendor_status);

                // Remove empty values
                $vendorStatuses = array_filter($vendorStatuses);

                // If no vendors exist, return requisition_status
                if (empty($vendorStatuses)) {
                    return $row->requisition_status ?? "No Status";
                }

                // If all vendors have the same status, return that status
                if (count(array_unique($vendorStatuses)) === 1) {
                    return $vendorStatuses[0]; // All vendors share the same status
                }

                // If vendors have mixed statuses, return "Complex Status"
                return "Complex Status";
            })
            ->orderColumn('requisition_no', function ($query, $order) {
                $query->orderBy('year_start', $order)
                    ->orderBy('year_end', $order)
                    ->orderBy('req_number', $order);
            })
            ->filterColumn('EmployeeName', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('requisition_status', function ($query, $keyword) {
                $query->whereRaw("requisitions.requisition_status like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }


    public function getCompletedRequisitions()
    {
        $requisitions = Requisition::leftJoin('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->leftJoin('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->leftJoin('requisition_vendors', 'requisitions.id', '=', 'requisition_vendors.requisition_id')
            ->select([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.source_of_funds',
                'requisitions.item',
                'users.name as EmployeeName',
                'departments.name as RequestingUnit',
                'requisitions.requisition_status' // Add requisition_status to the select
            ])
            ->where('requisitions.is_completed', true)
            ->selectRaw("
                CAST(cost_budgeting_requisitions.is_completed AS INT) AS is_completed,
                STRING_AGG(requisition_vendors.vendor_status, ', ') AS vendor_status, 
                COUNT(requisition_vendors.id) AS vendor_count,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->groupBy([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.source_of_funds',
                'requisitions.item',
                'users.name',
                'departments.name',
                'requisitions.requisition_status',
                'cost_budgeting_requisitions.is_completed'
            ]);


        // Restrict for Viewers (not Procurement or Office of the Permanent Secretary)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department->name !== 'Procurement Unit' && Auth::user()->department->name !== 'Office of the Permanent Secretary' && Auth::user()->department->name !== 'Office of the Permanent Secretary') {
            $requisitions->where('departments.name', Auth::user()->department->name);
        }

        return DataTables::of($requisitions)
            ->orderColumn('requisition_no', function ($query, $order) {
                $query->orderBy('year_start', $order)
                    ->orderBy('year_end', $order)
                    ->orderBy('req_number', $order);
            })
            ->filterColumn('EmployeeName', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('requisition_status', function ($query, $keyword) {
                $query->whereRaw("requisitions.requisition_status like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getInProgressRequisitions()
    {
        $requisitions = Requisition::leftJoin('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->leftJoin('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->leftJoin('requisition_vendors', 'requisitions.id', '=', 'requisition_vendors.requisition_id')
            ->select([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.item',
                'requisitions.source_of_funds',
                'users.name as EmployeeName',
                'departments.name as RequestingUnit',
                'requisitions.requisition_status',
                'requisitions.created_at',
                'requisitions.date_assigned'
            ])
            ->where('requisitions.is_completed', '!=', true)
            ->selectRaw("
                CAST(cost_budgeting_requisitions.is_completed AS INT) AS is_completed,
                STRING_AGG(requisition_vendors.vendor_status, ', ') AS vendor_status, 
                COUNT(requisition_vendors.id) AS vendor_count,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->groupBy([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.item',
                'requisitions.source_of_funds',
                'users.name',
                'departments.name',
                'requisitions.requisition_status',
                'cost_budgeting_requisitions.is_completed',
                'requisitions.created_at',
                'requisitions.date_assigned'
            ]);

        // Restrict for Viewers (not Procurement or Office of the Permanent Secretary)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department->name !== 'Procurement Unit' && Auth::user()->department->name !== 'Office of the Permanent Secretary' && Auth::user()->department->name !== 'Office of the Permanent Secretary') {
            $requisitions->where('departments.name', Auth::user()->department->name);
        }

        return DataTables::of($requisitions)
            ->editColumn('vendor_status', function ($row) {
                // If cost budgeting requisition is NOT completed, return requisition_status
                if (!$row->is_completed) {
                    return $row->requisition_status ?? "No Status";
                }

                // Convert vendor statuses into an array
                $vendorStatuses = explode(', ', $row->vendor_status);

                // Remove empty values
                $vendorStatuses = array_filter($vendorStatuses);

                // If no vendors exist, return requisition_status
                if (empty($vendorStatuses)) {
                    return $row->requisition_status ?? "No Status";
                }

                // If all vendors have the same status, return that status
                if (count(array_unique($vendorStatuses)) === 1) {
                    return $vendorStatuses[0]; // All vendors share the same status
                }

                // If vendors have mixed statuses, return "Complex Status"
                return "Complex Status";
            })
            ->orderColumn('requisition_no', function ($query, $order) {
                $query->orderBy('year_start', $order)
                    ->orderBy('year_end', $order)
                    ->orderBy('req_number', $order);
            })
            ->filterColumn('EmployeeName', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('requisition_status', function ($query, $keyword) {
                $query->whereRaw("requisitions.requisition_status like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}
