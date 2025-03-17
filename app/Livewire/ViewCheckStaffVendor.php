<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyChequeProcessing;
use App\Models\CheckStaffVendor;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewCheckStaffVendor extends Component
{
    public $vendor;
    public $requisition;
    public $cs_vendor;

    public $date_received_from_vc;
    public $voucher_destination;
    public $date_sent_audit;
    public $date_received_from_audit;
    public $date_sent_chequeprocessing;
    public $invoices;

    public $isEditing = true;
    public $accordionView = 'show';

    public function render()
    {
        return view('livewire.view-check-staff-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }

    public function mount($id)
    {
        $this->cs_vendor = CheckStaffVendor::find($id);
        $this->vendor = $this->cs_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->cs_vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        $this->date_received_from_vc = $this->vendor->date_received_from_vc;
        $this->voucher_destination = $this->vendor->voucher_destination;
        $this->date_sent_audit = $this->vendor->date_sent_audit;
        $this->date_received_from_audit = $this->vendor->date_received_from_audit;
        $this->date_sent_chequeprocessing = $this->vendor->date_sent_chequeprocessing;

        if ($this->date_received_from_vc !== null && $this->voucher_destination !== null && $this->date_sent_chequeprocessing !== null) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }
    public function getIsButtonDisabledProperty()
    {
        $isDisabled = true;

        if ($this->voucher_destination === 'Internal Audit') {
            $isDisabled = $this->date_received_from_vc === null || trim($this->date_received_from_vc) === '' ||
                $this->voucher_destination === null || trim($this->voucher_destination) === '' ||
                $this->date_sent_audit === null || trim($this->date_sent_audit) === '' ||
                $this->date_received_from_audit === null || trim($this->date_received_from_audit) === '' ||
                $this->date_sent_chequeprocessing === null || trim($this->date_sent_chequeprocessing) === '';
        } else {
            $isDisabled = $this->date_received_from_vc === null || trim($this->date_received_from_vc) === '' ||
                $this->voucher_destination === null || trim($this->voucher_destination) === '' ||
                $this->date_sent_chequeprocessing === null || trim($this->date_sent_chequeprocessing) === '';
        }
        return $isDisabled;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function edit()
    {
        if ($this->date_received_from_vc === '') {
            $this->date_received_from_vc = null;
        }

        if ($this->date_sent_audit === '') {
            $this->date_sent_audit = null;
        }

        if ($this->date_received_from_audit === '') {
            $this->date_received_from_audit = null;
        }

        if ($this->date_sent_chequeprocessing === '') {
            $this->date_sent_chequeprocessing = null;
        }

        if ($this->voucher_destination === 'Cheque Processing') {
            $this->date_sent_audit = null;
            $this->date_received_from_audit = null;
        }

        $this->validate(
            [
                'date_received_from_vc' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                'date_sent_audit' => 'nullable|date|after_or_equal:date_received_from_vc',
                'date_received_from_audit' => 'nullable|date|after_or_equal:date_sent_audit',
                'date_sent_chequeprocessing' => 'nullable|date|after_or_equal:date_received_from_audit',
            ],
            [
                'date_received_from_vc.after_or_equal' => 'Please check date.',
                'date_sent_audit.after_or_equal' => 'The Date Sent to Audit must be a date after or equal to the Date Received from Vote Control.',
                'date_received_from_audit.after_or_equal' => 'The Date Received from Audit must be a date after or equal to the Date Sent to Audit.',
                'date_sent_chequeprocessing.after_or_equal' => 'The Date Sent to Cheque Processing must be a date after or equal to the Date Received from Audit.',
            ]
        );

        try {
            $status = $this->vendor->vendor_status;

            if (!$this->cs_vendor->is_completed) {
                $status = $this->getStatus();
            }


            $this->vendor->update([
                'vendor_status' => $status,
                'date_received_from_vc' => $this->date_received_from_vc,
                'voucher_destination' => $this->voucher_destination,
                'date_sent_audit' => $this->date_sent_audit,
                'date_received_from_audit' => $this->date_received_from_audit,
                'date_sent_chequeprocessing' => $this->date_sent_chequeprocessing,
            ]);

            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Check Staff');
            $this->isEditing = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->vendor = $this->vendor->fresh();
            $this->cs_vendor = $this->cs_vendor->fresh();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Accounts Payable: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function getStatus()
    {

        $status = 'At Check Staff';

        if ($this->date_received_from_vc && !$this->cs_vendor->is_completed) {
            $status = 'Received by Check Staff';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && !$this->date_sent_audit) {
            $status = 'To be Sent to Internal Audit';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && $this->date_sent_audit && !$this->date_received_from_audit) {
            $status = 'Sent to Internal Audit';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && $this->date_sent_audit && $this->date_received_from_audit) {
            $status = 'To Be Sent to Cheque Processing';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Cheque Processing' && $this->date_sent_chequeprocessing) {
            $status = 'To Be Sent to Cheque Processing';
        }

        return $status;
    }

    public function sendToChequeProcessing()
    {
        $this->cs_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->update([
            'vendor_status' => 'Sent to Cheque Processing',
        ]);

        $this->vendor->chequeProcessing()->create([
            'date_received' => Carbon::now(),
        ]);

        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Cheque Processing by ' . Auth::user()->name . ' from Check Staff');

        //Get Cheque Processing Staff
        $chequeProcessingStaff = User::chequeProcessing()->get();
        foreach ($chequeProcessingStaff as $staff) {
            Mail::to($staff->email)->send(new NotifyChequeProcessing($this->vendor));
        }

        return redirect()->route('check_room.index')->with('success', 'Requisition sent to Cheque Processing successfully');
    }
}
