<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyVoteControl;
use App\Models\APVendor;
use App\Models\RequisitionVendor;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewAccountsPayableVendor extends Component
{
    public $ap_vendor;
    public $vendor;
    public $invoices;
    public $requisition;

    public $date_received_ap;
    public $date_sent_vc;

    public $isEditing = true;
    public $accordionView = 'show';

    public function mount($id)
    {
        $this->ap_vendor = APVendor::find($id);
        $this->vendor = $this->ap_vendor->vendor;
        $this->invoices = $this->vendor->invoices;

        if (!$this->vendor) {
            return abort(404);
        }

        $this->requisition = $this->vendor->requisition;

        $this->date_received_ap = $this->vendor->date_received_ap;
        $this->date_sent_vc = $this->vendor->date_sent_vc;

        if ($this->date_received_ap !== null && $this->date_sent_vc !== null) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-accounts-payable-vendor')->title($this->vendor->vendor_name . ' | View Vendor');
    }


    public function edit()
    {
        if ($this->date_received_ap == '') {
            $this->date_received_ap = null;
        }

        if ($this->date_sent_vc == '') {
            $this->date_sent_vc = null;
        }

        $this->validate(
            [
                'date_received_ap' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                'date_sent_vc' => 'nullable|date|after_or_equal:' . $this->date_received_ap,
            ],
            [
                'date_received_ap.after_or_equal' => 'Please check date',
                'date_sent_vc.after_or_equal' => 'Please check date',
            ]
        );

        try {
            $status = $this->getStatus();

            $this->vendor->update([
                'requisition_status' => $status,
                'date_received_ap' => $this->date_received_ap,
                'date_sent_vc' => $this->date_sent_vc,
            ]);

            Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name . ' from Accounts Payable');
            $this->isEditing = false;
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->requisition = $this->requisition->fresh();
            $this->ap_vendor = $this->ap_vendor->fresh();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Accounts Payable: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function getIsButtonDisabledProperty()
    {
        return $this->date_received_ap === null || $this->date_sent_vc === null;
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        $status = 'At Accounts Payable';

        if ($this->date_received_ap && $this->date_sent_vc && !$this->ap_vendor->is_completed) {
            $status = 'To Be Sent to Vote Control';
        }

        return $status;
    }
    public function toggleAccordionView()
    {
        $this->accordionView = $this->accordionView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }

    public function sendToVoteControl()
    {
        $this->vendor->update([
            'vendor_status' => 'Sent to Vote Control',
        ]);

        $this->ap_vendor->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->vendor->voteControl()->create([
            'date_received' => Carbon::now(),
        ]);

        //Send email to Vote Control
        Log::info('Vendor ' . $this->vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Vote Control by ' . Auth::user()->name . ' from Accounts Payable');

        //Get Vote Control users
        $users = User::voteControl()->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotifyVoteControl($this->vendor));
            // Log::info('Email sent to ' . $user->email . ' to notify them of Requisition #' . $this->requisition->requisition_no . ' sent to Vote Control by ' . Auth::user()->name . ' from Accounts Payable');
        }

        return redirect()->route('accounts_payable.index')->with('success', 'Sent to Vote Control successfully');
    }
}
