<?php

namespace App\Http\Controllers;

use App\Models\CheckRoomRequisition;
use App\Models\Requisition;
use App\Models\RequisitionVendor;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class CheckRoomController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-check-room-requisitions')) {
            return redirect()->route('/')->with('error', 'You do not have permission to view this page');
        }
        return view('requisitions.check-room');
    }

    public function getCheckStaffVendors(){
        $vendors = RequisitionVendor::join('check_staff_vendors', 'requisition_vendors.id', '=', 'check_staff_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'check_staff_vendors.id as cs_id', 'check_staff_vendors.is_completed as cs_completed', 'check_staff_vendors.created_at as cs_created_at');

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('check_room.view', $vendor->cs_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cs_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->checkStaff->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCheckRoomRequisitions()
    {
        $requisitions = Requisition::join('check_room_requisitions', 'requisitions.id', '=', 'check_room_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'check_room_requisitions.id as cr_id', 'check_room_requisitions.is_completed as cr_completed', 'check_room_requisitions.created_at as cr_created_at');


        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('check_room.view', $requisition->check_room_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cr_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->check_room_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCompletedCheckRoomRequisitions()
    {
        $requisitions = Requisition::join('check_room_requisitions', 'requisitions.id', '=', 'check_room_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'check_room_requisitions.id as cr_id', 'check_room_requisitions.is_completed as cr_completed', 'check_room_requisitions.created_at as cr_created_at')
            ->where('check_room_requisitions.is_completed', true);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('check_room.view', $requisition->check_room_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cr_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->check_room_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressCheckRoomRequisitions()
    {
        $requisitions = Requisition::join('check_room_requisitions', 'requisitions.id', '=', 'check_room_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'check_room_requisitions.id as cr_id', 'check_room_requisitions.is_completed as cr_completed', 'check_room_requisitions.created_at as cr_created_at')
            ->where('check_room_requisitions.is_completed', false);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($requisition) {
                return '<div style="text-align:center"><a href="' . route('check_room.view', $requisition->check_room_requisition->id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cr_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->check_room_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }
}
