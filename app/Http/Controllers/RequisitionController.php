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
            ->select('requisitions.*', 'users.name as EmployeeName', 'departments.name as RequestingUnit');

        //If the user is a Viewer and not in Procurement or PS Office, only show their department's requisitions i.e. For Jesse's account
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
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
            ->where('requisitions.is_completed', true);

        //If the user is a Viewer and not in Procurement or PS Office, only show their department's requisitions i.e. For Jesse's account
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
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
            ->where('requisitions.is_completed', '!=', true);

        //If the user is a Viewer and not in Procurement or PS Office, only show their department's requisitions i.e. For Jesse's account
        if (Auth::user()->role->name === 'Viewer' && Auth::user()->department !== 'Procurement' && Auth::user()->department !== 'PS Office') {
            $requisitions->where('departments.name', Auth::user()->department);
        }

        return DataTables::of($requisitions)
            ->filterColumn('EmployeeName', function ($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}
