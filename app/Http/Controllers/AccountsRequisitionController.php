<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AccountsRequisitionController extends Controller
{
    public function index()
    {
        return view('requisitions.accounts_requisitions');
    }

    public function getAccountsRequisitions()
    {
        $requisitions = Requisition::join('accounts_requisitions', 'requisitions.id', '=', 'accounts_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'accounts_requisitions.id as ar_id');

        return DataTables::of($requisitions)
        ->filterColumn('RequestingUnit', function($query, $keyword) {
            $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
        })
        ->filterColumn('ItemName', function($query, $keyword) {
            $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
        })
        ->make(true);
    }
}
