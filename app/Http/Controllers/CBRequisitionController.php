<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class CBRequisitionController extends Controller
{

    public function index()
    {
        return view('requisitions.cost_and_budgeting');
    }

    public function getCostAndBudgetingRequisitions(){
        $requisitions = Requisition::join('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cost_budgeting_requisitions.id as cb_id', 
            'cost_budgeting_requisitions.is_completed as cb_completed', 'cost_budgeting_requisitions.created_at as cb_created_at');

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getCompletedCostAndBudgetingRequisitions(){
        $requisitions = Requisition::join('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cost_budgeting_requisitions.id as cb_id', 
            'cost_budgeting_requisitions.is_completed as cb_completed', 'cost_budgeting_requisitions.created_at as cb_created_at')
            ->where('cost_budgeting_requisitions.is_completed', true);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getInProgressCostAndBudgetingRequisitions()
    {
        $requisitions = Requisition::join('cost_budgeting_requisitions', 'requisitions.id', '=', 'cost_budgeting_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cost_budgeting_requisitions.id as cb_id', 
            'cost_budgeting_requisitions.is_completed as cb_completed', 'cost_budgeting_requisitions.created_at as cb_created_at')
            ->where('cost_budgeting_requisitions.is_completed', false);

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
