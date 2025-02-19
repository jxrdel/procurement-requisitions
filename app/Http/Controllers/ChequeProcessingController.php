<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionVendor;
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

    public function getChequeProcessingVendors()
    {
        $vendors = RequisitionVendor::join('cheque_processing_vendors', 'requisition_vendors.id', '=', 'cheque_processing_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select(
                'requisition_vendors.*',
                'requisition_vendors.vendor_name as VendorName',
                'requisition_vendors.vendor_status as VendorStatus',
                'requisitions.item as ItemName',
                'requisitions.requisition_no as RequisitionNo',
                'requisitions.requisition_status as requisition_status',
                'departments.name as RequestingUnit',
                'cheque_processing_vendors.id as cp_id',
                'cheque_processing_vendors.is_completed as cp_completed',
                'cheque_processing_vendors.created_at as cp_created_at'
            );

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $vendor->cp_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->chequeProcessing->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCompletedChequeProcessingVendors()
    {
        $vendors = RequisitionVendor::join('cheque_processing_vendors', 'requisition_vendors.id', '=', 'cheque_processing_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'cheque_processing_vendors.id as cp_id', 'cheque_processing_vendors.is_completed as cp_completed', 'cheque_processing_vendors.created_at as cp_created_at')
            ->where('cheque_processing_vendors.is_completed', true);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $vendor->cp_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->chequeProcessing->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressChequeProcessingVendors()
    {
        $vendors = RequisitionVendor::join('cheque_processing_vendors', 'requisition_vendors.id', '=', 'cheque_processing_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'cheque_processing_vendors.id as cp_id', 'cheque_processing_vendors.is_completed as cp_completed', 'cheque_processing_vendors.created_at as cp_created_at')
            ->where('cheque_processing_vendors.is_completed', false);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('cheque_processing.view', $vendor->cp_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('cp_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->chequeProcessing->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }
}
