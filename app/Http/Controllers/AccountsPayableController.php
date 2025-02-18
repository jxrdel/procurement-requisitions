<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use App\Models\RequisitionVendor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Yajra\DataTables\DataTables;

class AccountsPayableController extends Controller
{
    public function index()
    {
        if (Gate::denies('view-accounts-payable-requisitions')) {
            return redirect()->route('/')->with('error', 'You are not authorized to view this page');
        }
        return view('requisitions.accounts-payable');
    }

    public function getAccountsPayableVendors()
    {
        $vendors = RequisitionVendor::join('ap_vendors', 'requisition_vendors.id', '=', 'ap_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'ap_vendors.id as ap_id', 'ap_vendors.is_completed as ap_completed', 'ap_vendors.created_at as ap_created_at');

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('accounts_payable.view', $vendor->ap_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('ap_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->ap->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getCompletedAccountsPayableVendors()
    {
        $vendors = RequisitionVendor::join('ap_vendors', 'requisition_vendors.id', '=', 'ap_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'ap_vendors.id as ap_id', 'ap_vendors.is_completed as ap_completed', 'ap_vendors.created_at as ap_created_at')
            ->where('ap_vendors.is_completed', true);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('accounts_payable.view', $vendor->ap_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('ap_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->ap->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressAccountsPayableVendors()
    {
        $vendors = RequisitionVendor::join('ap_vendors', 'requisition_vendors.id', '=', 'ap_vendors.vendor_id')
            ->join('requisitions', 'requisition_vendors.requisition_id', '=', 'requisitions.id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisition_vendors.*', 'requisition_vendors.vendor_name as VendorName', 'requisition_vendors.vendor_status as VendorStatus', 'requisitions.item as ItemName', 'requisitions.requisition_no as RequisitionNo', 'requisitions.requisition_status as requisition_status', 'departments.name as RequestingUnit', 'ap_vendors.id as ap_id', 'ap_vendors.is_completed as ap_completed', 'ap_vendors.created_at as ap_created_at')
            ->where('ap_vendors.is_completed', false);

        return DataTables::of($vendors)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->addColumn('action', function ($vendor) {
                return '<div style="text-align:center"><a href="' . route('accounts_payable.view', $vendor->ap_id) . '" class="btn btn-primary btn-sm">View</a></div>';
            })
            ->editColumn('ap_created_at', function ($vendor) {
                $date = Carbon::parse($vendor->ap->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }

    public function getInProgressAccountsPayableRequisitions()
    {
        $requisitions = Requisition::join('ap_requisitions', 'requisitions.id', '=', 'ap_requisitions.requisition_id')
            ->join('departments', 'requisitions.requesting_unit', '=', 'departments.id')
            ->select('requisitions.*', 'requisitions.item as ItemName', 'departments.name as RequestingUnit', 'ap_requisitions.id as ap_id', 'ap_requisitions.is_completed as ap_completed', 'ap_requisitions.created_at as ap_created_at')
            ->where('ap_requisitions.is_completed', false);

        return DataTables::of($requisitions)
            ->filterColumn('RequestingUnit', function ($query, $keyword) {
                $query->whereRaw("departments.name like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('ItemName', function ($query, $keyword) {
                $query->whereRaw("requisitions.item like ?", ["%{$keyword}%"]);
            })
            ->editColumn('ap_created_at', function ($requisition) {
                $date = Carbon::parse($requisition->ap_requisition->created_at)->format('d/m/Y');
                return $date;
            })
            ->make(true);
    }
}
