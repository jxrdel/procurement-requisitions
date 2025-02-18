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

    public $vendors = [];
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
        $this->vendors = $this->requisition->vendors()->select('id', 'vendor_name', 'amount', 'change_of_vote_no', 'batch_no', 'voucher_no', 'date_sent_checkstaff')->get()->toArray();

        //Add accordion view to each vendor
        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'hide';
        }

        foreach ($this->vendors as $vendor) {
            if ($vendor['batch_no'] !== null && $vendor['voucher_no'] !== null && $vendor['date_sent_checkstaff'] !== null) {
                $this->isEditing = false;
                break;
            }
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
        foreach ($this->vendors as $vendor) {
            if ($vendor['voucher_no'] === null || trim($vendor['voucher_no']) === '' || $vendor['batch_no'] === null || trim($vendor['batch_no']) === '' || $vendor['date_sent_checkstaff'] === null || trim($vendor['date_sent_checkstaff']) === '') {
                return true;
            }
        }

        return false;
    }

    //Edit funciton

    public function edit()
    {

        foreach ($this->vendors as &$vendor) {
            if ($vendor['date_sent_checkstaff'] == '') {
                $vendor['date_sent_checkstaff'] = null;
            }
        }

        unset($vendor);

        $status = $this->getStatus();

        foreach ($this->vendors as $vendor) {
            $this->requisition->vendors()->where('id', $vendor['id'])->update([
                'batch_no' => $vendor['batch_no'],
                'voucher_no' => $vendor['voucher_no'],
                'date_sent_checkstaff' => $vendor['date_sent_checkstaff'],
            ]);
        }

        $this->requisition->update([
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
            // Mail::to($staff->email)->queue(new NotifyCheckRoom($this->requisition));
        }

        return redirect()->route('vote_control.index')->with('success', 'Requisition sent to Check Staff successfully');
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        // Define priority levels for sorting (lower value = earlier stage)
        $priority = [
            'At Vote Control' => 1,
            'To Be Sent to Check Staff' => 2,
        ];

        $statuses = collect($this->vendors)->map(function ($vendor) {
            if ($vendor['batch_no'] && $vendor['voucher_no'] && !$this->vc_requisition->is_completed) {
                return 'To Be Sent to Check Staff';
            }
            return 'At Vote Control';
        });

        return $statuses->sortBy(fn($status) => $priority[$status])->first() ?? 'At Vote Control';
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

    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
