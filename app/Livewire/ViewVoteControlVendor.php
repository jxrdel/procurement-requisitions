<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyCheckRoom;
use App\Models\RequisitionVendor;
use App\Models\User;
use App\Models\VoteControlVendor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewVoteControlVendor extends Component
{
    public $vendor;
    public $vc_vendor;
    public $requisition;
    public $voucher_no;
    public $batch_no;
    public $date_sent_checkstaff;
    public $invoices;

    public $isEditing = true;
    public $accordionView = 'show';
    public function mount($id)
    {
        $this->vc_vendor = VoteControlVendor::find($id);
        $this->vendor = $this->vc_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->vc_vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        $this->batch_no = $this->vendor->batch_no;
        $this->voucher_no = $this->vendor->voucher_no;
        $this->date_sent_checkstaff = $this->vendor->date_sent_checkstaff;

        if (($this->batch_no !== null || trim($this->batch_no) == '') && ($this->voucher_no !== null && trim($this->voucher_no) !== '')) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-vote-control-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function getIsButtonDisabledProperty()
    {
        return
            $this->voucher_no === null || trim($this->voucher_no) === '' ||
            $this->batch_no === null || trim($this->batch_no) === '' ||
            $this->date_sent_checkstaff === null;
    }
    public function edit()
    {
        try {
            //Check if the date_received_ap is empty and set it to null
            if ($this->date_sent_checkstaff == '') {
                $this->date_sent_checkstaff = null;
            }

            $status = $this->vendor->vendor_status;

            if (!$this->vc_vendor->is_completed) {
                $status = $this->getStatus();
            }


            $this->vendor->update([
                'batch_no' => $this->batch_no,
                'voucher_no' => $this->voucher_no,
                'date_sent_checkstaff' => $this->date_sent_checkstaff,
                'vendor_status' => $status,
            ]);

            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Vote Control');
            $this->isEditing = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->vendor = $this->vendor->fresh();
            $this->vc_vendor = $this->vc_vendor->fresh();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Accounts Payable: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function getStatus()
    {
        $status = 'At Vote Control';

        if ($this->batch_no && $this->voucher_no && !$this->vc_vendor->is_completed) {
            $status = 'To Be Sent to Check Staff';
        }

        return $status;
    }

    public function sendToCheckStaff()
    {
        $this->vc_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->update([
            'vendor_status' => 'Sent to Check Staff',
        ]);

        $this->vendor->checkStaff()->create([
            'date_received' => Carbon::now(),
        ]);

        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Check Staff by ' . Auth::user()->name . ' from Vote Control');

        //Get Emails of Check Staff
        $checkStaff = User::checkStaff()->get();
        foreach ($checkStaff as $staff) {
            Mail::to($staff->email)->queue(new NotifyCheckRoom($this->vendor));
        }

        return redirect()->route('vote_control.index')->with('success', 'Sent to Check Staff successfully');
    }

    public function toggleAccordionView()
    {
        $this->accordionView = $this->accordionView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
