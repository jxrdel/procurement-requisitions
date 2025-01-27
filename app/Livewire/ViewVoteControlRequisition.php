<?php

namespace App\Livewire;

use App\Mail\NotifyCheckRoom;
use App\Mail\RequisitionCompleted;
use App\Models\VoteControlRequisition;
use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewVoteControlRequisition extends Component
{
    public $requisition;
    public $vc_requisition;

    public $batch_no;
    public $voucher_no;
    public $date_sent_checkstaff;

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
        $this->date_sent_checkstaff = $this->requisition->date_sent_checkstaff;

        if ($this->batch_no !== null && $this->voucher_no !== null) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-vote-control-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function getIsButtonDisabledProperty()
    {
        return
            $this->voucher_no === null || trim($this->voucher_no) === '' ||
            $this->batch_no === null || trim($this->batch_no) === '' ||
            $this->date_sent_checkstaff === null || trim($this->date_sent_checkstaff) === '';
    }

    //Edit funciton

    public function edit()
    {

        $status = $this->getStatus();

        $this->requisition->update([
            'batch_no' => $this->batch_no,
            'voucher_no' => $this->voucher_no,
            'date_sent_checkstaff' => $this->date_sent_checkstaff,
            'requisition_status' => $status,
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
            'requisition_status' => 'Sent to Check Staff',
        ]);

        //Get Emails of Check Staff
        $checkStaff = User::checkStaff()->get();
        foreach ($checkStaff as $staff) {
            Mail::to($staff->email)->queue(new NotifyCheckRoom($this->requisition));
        }

        return redirect()->route('vote_control.index')->with('success', 'Requisition sent to Check Staff successfully');
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        $status = 'At Vote Control';

        if ($this->batch_no && $this->voucher_no && !$this->vc_requisition->is_completed) {
            $status = 'To Be Sent to Check Staff';
        }

        return $status;
    }

    public function getDateSentVC()
    {
        if ($this->vc_requisition->date_received) {
            return Carbon::parse($this->vc_requisition->date_received)->format('F jS Y');
        }
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }
}
