<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RequisitionController extends Controller
{
    public function index()
    {
        return view('requisitions.index');
    }

    public function getRequisitions()
    {
        $requisitions = Requisition::join('users', 'requisitions.assigned_to', '=', 'users.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'users.name as EmployeeName', 'departments.name as RequestingUnit');

        return DataTables::of($requisitions)
            ->filterColumn('EmployeeName', function($query, $keyword) {
                $query->whereRaw("users.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('RequestingUnit', function($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}
