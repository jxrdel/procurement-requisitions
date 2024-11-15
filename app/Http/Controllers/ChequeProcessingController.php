<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class ChequeProcessingController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-cheque-processing-requisitions')) {
            return redirect()->route('/')->with('error', 'You do not have permission to view this page');
        }
        return view('requisitions.cheque-processing');
    }

    public function getChequeProcessingRequisitions()
    {

        $requisitions = Requisition::join('cheque_processing_requisitions', 'requisitions.id', '=', 'cheque_processing_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cheque_processing_requisitions.id as cp_id', 'cheque_processing_requisitions.is_completed as cp_completed', 'cheque_processing_requisitions.created_at as cp_created_at');

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $requisition->cheque_processing_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->cheque_processing_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCompletedChequeProcessingRequisitions()
    {
        $requisitions = Requisition::join('cheque_processing_requisitions', 'requisitions.id', '=', 'cheque_processing_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cheque_processing_requisitions.id as cp_id', 'cheque_processing_requisitions.is_completed as cp_completed', 'cheque_processing_requisitions.created_at as cp_created_at')
            ->where('cheque_processing_requisitions.is_completed', true);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $requisition->cheque_processing_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->cheque_processing_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressChequeProcessingRequisitions()
    {
        $requisitions = Requisition::join('cheque_processing_requisitions', 'requisitions.id', '=', 'cheque_processing_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'cheque_processing_requisitions.id as cp_id', 'cheque_processing_requisitions.is_completed as cp_completed', 'cheque_processing_requisitions.created_at as cp_created_at')
            ->where('cheque_processing_requisitions.is_completed', false);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $requisition->cheque_processing_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->cheque_processing_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }
}
