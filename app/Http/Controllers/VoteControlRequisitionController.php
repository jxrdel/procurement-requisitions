<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionVendor;
use Carbon\Carbon;
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

    public function getVoteControlVendors()
    {
        $vendors = RequisitionVendor::join('vote_control_vendors', 'requisition_vendors.id', '=', 'vote_control_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'vote_control_vendors.id as vc_id', 'vote_control_vendors.is_completed as vc_completed', 'vote_control_vendors.created_at as vc_created_at');

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('vote_control.view', $vendor->vc_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('vc_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->voteControl->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCompletedVoteControlVendors()
    {
        $vendors = RequisitionVendor::join('vote_control_vendors', 'requisition_vendors.id', '=', 'vote_control_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'vote_control_vendors.id as vc_id', 'vote_control_vendors.is_completed as vc_completed', 'vote_control_vendors.created_at as vc_created_at')
            ->where('vote_control_vendors.is_completed', true);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('vote_control.view', $vendor->vc_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('vc_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->voteControl->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressVoteControlVendors()
    {
        $vendors = RequisitionVendor::join('vote_control_vendors', 'requisition_vendors.id', '=', 'vote_control_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'vote_control_vendors.id as vc_id', 'vote_control_vendors.is_completed as vc_completed', 'vote_control_vendors.created_at as vc_created_at')
            ->where('vote_control_vendors.is_completed', false);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('vote_control.view', $vendor->vc_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('vc_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->voteControl->created_at)->format('d/m/Y');
                return $date;
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
