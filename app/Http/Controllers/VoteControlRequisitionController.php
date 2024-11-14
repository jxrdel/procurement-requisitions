<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class VoteControlRequisitionController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-vote-control-requisitions')) {
            return redirect()->route('/')->with('error', 'You are not authorized to view this page');
        }
        return view('requisitions.vote_control_requisitions');
    }

    public function getVoteControlRequisitions()
    {
        $requisitions = Requisition::join('vote_control_requisitions', 'requisitions.id', '=', 'vote_control_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'vote_control_requisitions.id as ar_id', 'vote_control_requisitions.is_completed as ar_completed', 'vote_control_requisitions.created_at as ar_created_at');

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getCompletedVoteControlRequisitions()
    {
        $requisitions = Requisition::join('vote_control_requisitions', 'requisitions.id', '=', 'vote_control_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select(
                'requisitions.*',
                'requisitions.item as ItemName',
                'departments.name as RequestingUnit',
                'vote_control_requisitions.id as ar_id',
                'vote_control_requisitions.is_completed as ar_completed',
                'vote_control_requisitions.created_at as ar_created_at'
            )
            ->where('vote_control_requisitions.is_completed', true);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->make(true);
    }

    public function getInProgressVoteControlRequisitions()
    {
        $requisitions = Requisition::join('vote_control_requisitions', 'requisitions.id', '=', 'vote_control_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'vote_control_requisitions.id as ar_id', 'vote_control_requisitions.is_completed as ar_completed', 'vote_control_requisitions.created_at as ar_created_at')
            ->where('vote_control_requisitions.is_completed', false);

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
