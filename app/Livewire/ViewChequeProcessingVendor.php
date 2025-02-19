<?php

namespace App\Livewire;

use App\Mail\RequisitionCompleted;
use App\Models\ChequeProcessingVendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewChequeProcessingVendor extends Component
{
    public $vendor;
    public $requisition;
    public $cp_vendor;

    public $date_cheque_processed;
    public $cheque_no;
    public $date_of_cheque;
    public $date_sent_dispatch;

    public $isEditing = true;

    public function render()
    {
        return view('livewire.view-cheque-processing-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }

    public function mount($id)
    {
        $this->cp_vendor = ChequeProcessingVendor::find($id);
        $this->vendor = $this->cp_vendor->vendor;

        if (!$this->cp_vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        $this->date_cheque_processed = $this->vendor->date_cheque_processed;
        $this->cheque_no = $this->vendor->cheque_no;
        $this->date_of_cheque = $this->vendor->date_of_cheque;
        $this->date_sent_dispatch = $this->vendor->date_sent_dispatch;

        if ($this->date_cheque_processed !== null && $this->cheque_no !== null && $this->date_of_cheque !== null && $this->date_sent_dispatch !== null) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function getIsButtonDisabledProperty()
    {
        return $this->date_cheque_processed === null || $this->cheque_no === null || $this->date_of_cheque === null || $this->date_sent_dispatch === null;
    }

    public function edit()
    {

        if ($this->date_cheque_processed === '') {
            $this->date_cheque_processed = null;
        }

        if ($this->date_of_cheque === '') {
            $this->date_of_cheque = null;
        }

        if ($this->date_sent_dispatch === '') {
            $this->date_sent_dispatch = null;
        }

        if (trim($this->cheque_no) === '') {
            $this->cheque_no = null;
        }

        $status = $this->vendor->vendor_status;

        if (!$this->cp_vendor->is_completed) {
            $status = $this->getStatus();
        }

        $this->validate([
            'date_cheque_processed' => 'nullable|date',
            'date_of_cheque' => 'nullable|date',
            'date_sent_dispatch' => 'nullable|date',
        ]);

        $this->vendor->update([
            'vendor_status' => $status,
            'date_cheque_processed' => $this->date_cheque_processed,
            'cheque_no' => $this->cheque_no,
            'date_of_cheque' => $this->date_of_cheque,
            'date_sent_dispatch' => $this->date_sent_dispatch,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->vendor = $this->vendor->fresh();
        $this->cp_vendor = $this->cp_vendor->fresh();
    }

    public function getStatus()
    {

        $status = 'At Cheque Processing';

        if ($this->date_cheque_processed && !$this->cp_vendor->is_completed) {
            $status = 'Cheque Details to be Entered';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && !$this->date_sent_dispatch) {
            $status = 'To be Sent to Cheque Dispatch';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && $this->date_sent_dispatch) {
            $status = 'To Be Completed by Cheque Processing';
        }

        return $status;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function completeVendor()
    {
        sleep(1);
        $this->cp_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->update([
            'vendor_status' => 'Completed',
            'is_completed' => true,
            'date_completed' => now(),
        ]);


        //Check if all vendor status are completed
        if ($this->requisition->isCompleted()) {
            $this->requisition->update([
                'requisition_status' => 'Completed',
            ]);

            $assigned_to = $this->requisition->procurement_officer;
            if ($assigned_to) {
                Mail::to($assigned_to->email)->cc('maryann.basdeo@health.gov.tt')->queue(new RequisitionCompleted($this->requisition));
            } else {
                Mail::to('maryann.basdeo@health.gov.tt')->queue(new RequisitionCompleted($this->requisition));
            }
        }

        $this->dispatch('show-message', message: 'Vendor marked as completed');
        $this->cp_vendor = $this->cp_vendor->fresh();
        $this->vendor = $this->vendor->fresh();
    }
}
