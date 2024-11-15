<?php

namespace App\Livewire;

use App\Mail\NotifyCheckRoom;
use App\Mail\RequisitionCompleted;
use App\Models\VoteControlRequisition;
use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewVoteControlRequisition extends Component
{
    public $requisition;
    public $vc_requisition;

    public $batch_no;
    public $voucher_no;
    public $date_received_from_vc;
    public $date_of_cheque;
    public $cheque_no;
    public $date_sent_chequeprocessing;

    public $isEditing = true;

    public function mount($id)
    {
        $this->vc_requisition = VoteControlRequisition::find($id);

        if (!$this->vc_requisition) {
            return abort(404);
        }

        $this->requisition = $this->vc_requisition->requisition;

        $this->batch_no = $this->requisition->batch_no;
        $this->voucher_no = $this->requisition->voucher_no;
        $this->date_received_from_vc = $this->requisition->date_received_from_vc;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_sent_chequeprocessing = $this->requisition->date_sent_chequeprocessing;

        if ($this->batch_no !== null && $this->voucher_no !== null) {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-vote-control-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function getFormattedDateSentChequeroom()
    {
        if ($this->date_received_from_vc) {
            return Carbon::parse($this->date_received_from_vc)->format('F jS, Y');
        }
    }

    public function getFormattedDateOfCheque()
    {
        if ($this->date_of_cheque) {
            return Carbon::parse($this->date_of_cheque)->format('F jS, Y');
        }
    }

    public function getFormattedDateChequeForwarded()
    {
        if ($this->date_sent_chequeprocessing) {
            return Carbon::parse($this->date_sent_chequeprocessing)->format('F jS, Y');
        }
    }
    public function getIsButtonDisabledProperty()
    {
        return
            $this->voucher_no === null || trim($this->voucher_no) === '' ||
            $this->batch_no === null || trim($this->batch_no) === '';
    }

    //Edit funciton

    public function edit()
    {

        if ($this->date_received_from_vc === '') {
            $this->date_received_from_vc = null;
        }

        if ($this->date_of_cheque === '') {
            $this->date_of_cheque = null;
        }

        if ($this->cheque_no === '') {
            $this->cheque_no = null;
        }

        if ($this->date_sent_chequeprocessing === '') {
            $this->date_sent_chequeprocessing = null;
        }

        $status = $this->getStatus();

        $this->validate(
            [
                'date_received_from_vc' => 'nullable|date|after_or_equal:' . $this->vc_requisition->date_received,
                'date_of_cheque' => 'nullable|date|after_or_equal:date_received_from_vc',
                'date_sent_chequeprocessing' => 'nullable|date|after_or_equal:date_of_cheque',
            ],
            [
                'date_received_from_vc.after_or_equal' => 'Date received from Vote Control must be after or equal to the date received from Vote Control',
                'date_of_cheque.after_or_equal' => 'Date of cheque must be after or equal to the date received from Vote Control',
                'date_sent_chequeprocessing.after_or_equal' => 'Date sent to cheque processing must be after or equal to the date of cheque',
            ]
        );

        $this->requisition->update([
            'batch_no' => $this->batch_no,
            'voucher_no' => $this->voucher_no,
            'requisition_status' => $status,
            'date_received_from_vc' => $this->date_received_from_vc,
            'date_of_cheque' => $this->date_of_cheque,
            'cheque_no' => trim($this->cheque_no),
            'date_sent_chequeprocessing' => $this->date_sent_chequeprocessing,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->vc_requisition = $this->vc_requisition->fresh();
    }

    public function sendToCheckRoom()
    {
        $this->vc_requisition->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->requisition->check_room_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        $this->requisition->update([
            'requisition_status' => 'Sent to Check Room',
        ]);

        //Get Emails of Check Staff
        $checkStaff = User::checkStaff()->get();
        foreach ($checkStaff as $staff) {
            Mail::to($staff->email)->send(new NotifyCheckRoom($this->requisition));
        }

        return redirect()->route('vote_control.index')->with('success', 'Requisition sent to Check Room successfully');
    }

    public function getStatus()
    {
        $status = 'At Vote Control';

        if ($this->batch_no && $this->voucher_no && !$this->vc_requisition->is_completed) {
            $status = 'To Be Completed by Vote Control';
        }

        return $status;
    }

    public function getDateSentVC()
    {
        if ($this->vc_requisition->date_received) {
            return Carbon::parse($this->vc_requisition->date_received)->format('F jS Y');
        }
    }
}
