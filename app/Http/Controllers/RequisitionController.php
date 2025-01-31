<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            ->select('requisitions.*', 'users.name as EmployeeName', 'departments.name as RequestingUnit')
            ->selectRaw("
                requisitions.requisition_no,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ");
    
        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
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
            ->make(true);
    }

    public function getCompletedRequisitions()
    {
        $requisitions = Requisition::leftJoin('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'users.name as EmployeeName', 'departments.name as RequestingUnit')
            ->selectRaw("
                requisitions.requisition_no,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->where('requisitions.is_completed', true);
    
        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
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
            ->make(true);
    }

    public function getInProgressRequisitions()
    {
        $requisitions = Requisition::leftJoin('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'users.name as EmployeeName', 'departments.name as RequestingUnit')
            ->selectRaw("
                requisitions.requisition_no,
                CAST(LEFT(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) - 1) AS INT) AS req_number,
                CAST(SUBSTRING(requisitions.requisition_no, CHARINDEX('-', requisitions.requisition_no) + 1, 2) AS INT) AS year_start,
                CAST(RIGHT(requisitions.requisition_no, 2) AS INT) AS year_end
            ")
            ->where('requisitions.is_completed', '!=', true);
    
        // Restrict for Viewers (not Procurement or PS Office)
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
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
            ->make(true);
    }
}
