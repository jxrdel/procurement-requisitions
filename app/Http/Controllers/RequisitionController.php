<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RequisitionController extends Controller
{
    public function index()
    {
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
                'requisitions.requisition_status' // Add requisition_status to the select
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
                'requisitions.requisition_status', // Group by requisition_status as well
                'cost_budgeting_requisitions.is_completed'
            ]);


        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
            ->editColumn('vendor_status', function ($row) {
                if ($row->vendor_count > 1 && $row->is_completed) {
                    return "Complex Status"; // More than 1 vendor and completed
                }
                // If no vendors, return requisition_status instead of vendor_status
                if ($row->vendor_count == 0) {
                    return $row->requisition_status ?? "No Status"; // Fallback to requisition_status
                }
                if ($row->vendor_count == 1 && $row->is_completed) {
                    return $row->vendor_status ?? "No Status"; // If there is only 1 vendor and the Cost Budgeting Requisition is completed, display the vendor status
                }
                if ($row->vendor_count == 1) {
                    return $row->requisition_status ?? "No Status"; // Fallback to requisition_status
                }
                return $row->vendor_status ?? "No Vendor"; // Show single vendor status or "No Vendor"
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
            ->selectRaw("
                CAST(cost_budgeting_requisitions.is_completed AS INT) AS is_completed,
                STRING_AGG(requisition_vendors.vendor_status, ', ') AS vendor_status, 
                COUNT(requisition_vendors.id) AS vendor_count,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->where('requisitions.is_completed', true)
            ->groupBy([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.source_of_funds',
                'requisitions.item',
                'users.name',
                'departments.name',
                'requisitions.requisition_status', // Group by requisition_status as well
                'cost_budgeting_requisitions.is_completed'
            ]);


        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
            ->editColumn('vendor_status', function ($row) {
                if ($row->vendor_count > 1 && $row->is_completed) {
                    return "Complex Status"; // More than 1 vendor and completed
                }
                // If no vendors, return requisition_status instead of vendor_status
                if ($row->vendor_count == 0) {
                    return $row->requisition_status ?? "No Status"; // Fallback to requisition_status
                }
                if ($row->vendor_count == 1 && $row->is_completed) {
                    return $row->vendor_status ?? "No Status"; // If there is only 1 vendor and the Cost Budgeting Requisition is completed, display the vendor status
                }
                if ($row->vendor_count == 1) {
                    return $row->requisition_status ?? "No Status"; // Fallback to requisition_status
                }
                return $row->vendor_status ?? "No Vendor"; // Show single vendor status or "No Vendor"
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
                'requisitions.source_of_funds',
                'requisitions.item',
                'users.name as EmployeeName',
                'departments.name as RequestingUnit',
                'requisitions.requisition_status' // Add requisition_status to the select
            ])
            ->selectRaw("
                CAST(cost_budgeting_requisitions.is_completed AS INT) AS is_completed,
                STRING_AGG(requisition_vendors.vendor_status, ', ') AS vendor_status, 
                COUNT(requisition_vendors.id) AS vendor_count,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->where('requisitions.is_completed', '!=', true)
            ->groupBy([
                'requisitions.id',
                'requisitions.requisition_no',
                'requisitions.source_of_funds',
                'requisitions.item',
                'users.name',
                'departments.name',
                'requisitions.requisition_status', // Group by requisition_status as well
                'cost_budgeting_requisitions.is_completed'
            ]);


        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
            ->editColumn('vendor_status', function ($row) {
                if ($row->vendor_count > 1 && $row->is_completed) {
                    return "Complex Status"; // More than 1 vendor and completed
                }
                // If no vendors, return requisition_status instead of vendor_status
                if ($row->vendor_count == 0) {
                    return $row->requisition_status ?? "No Status"; // Fallback to requisition_status
                }
                if ($row->vendor_count == 1 && $row->is_completed) {
                    return $row->vendor_status ?? "No Status"; // If there is only 1 vendor and the Cost Budgeting Requisition is completed, display the vendor status
                }
                if ($row->vendor_count == 1) {
                    return $row->vendor_status ?? "No Status"; // Fallback to requisition_status
                }
                return $row->vendor_status ?? "No Vendor"; // Show single vendor status or "No Vendor"
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
            ->make(true);
    }
}
