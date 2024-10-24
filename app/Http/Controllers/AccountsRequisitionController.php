<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AccountsRequisitionController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-cheque-dispatch-requisition')) {
            return redirect()->route('/')->with('error', 'You are not authorized to view this page');
        }
        return view('requisitions.accounts_requisitions');
    }

    public function getAccountsRequisitions()
    {
        $requisitions = Requisition::join('accounts_requisitions', 'requisitions.id', '=', 'accounts_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'accounts_requisitions.id as ar_id', 'accounts_requisitions.is_completed as ar_completed', 'accounts_requisitions.created_at as ar_created_at');

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getCompletedAccountsRequisitions()
    {
        $requisitions = Requisition::join('accounts_requisitions', 'requisitions.id', '=', 'accounts_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select(
                'requisitions.*',
                'requisitions.item as ItemName',
                'departments.name as RequestingUnit',
                'accounts_requisitions.id as ar_id',
                'accounts_requisitions.is_completed as ar_completed',
                'accounts_requisitions.created_at as ar_created_at'
            )
            ->where('accounts_requisitions.is_completed', true);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getInProgressAccountsRequisitions()
    {
        $requisitions = Requisition::join('accounts_requisitions', 'requisitions.id', '=', 'accounts_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'accounts_requisitions.id as ar_id', 'accounts_requisitions.is_completed as ar_completed', 'accounts_requisitions.created_at as ar_created_at')
            ->where('accounts_requisitions.is_completed', false);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }
}
