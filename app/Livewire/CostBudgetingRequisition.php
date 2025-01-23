<?php

namespace App\Livewire;

use App\Mail\CostBudgetingCompleted;
use App\Models\CBRequisition;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CostBudgetingRequisition extends Component
{
    public $cb_requisition;
    public $requisition;

    public $date_sent_request_mof;
    public $request_category;
    public $request_no;
    public $release_type;
    public $release_no;
    public $release_date;
    public $change_of_vote_no;

    //Requisition Details

    public $requisition_no;
    public $file_no;
    public $item;
    public $source_of_funds;
    public $date_assigned;
    public $date_sent_dps;
    public $ps_approval;
    public $vendor_name;
    public $amount;
    public $logs;
    public $logdetails;

    public $isEditing = true;
    public $votes;

    public function mount($id)
    {
        $this->cb_requisition = CBRequisition::find($id);
        $this->votes = Vote::all();

        if (!$this->cb_requisition) {
            return abort(404);
        }

        $this->requisition = Requisition::find($this->cb_requisition->requisition_id);

        $this->date_sent_request_mof = $this->requisition->date_sent_request_mof;
        $this->request_category = $this->requisition->request_category;
        $this->request_no = $this->requisition->request_no;
        $this->release_type = $this->requisition->release_type;
        $this->release_no = $this->requisition->release_no;
        $this->release_date = $this->requisition->release_date;
        $this->change_of_vote_no = $this->requisition->change_of_vote_no;

        //Requisition Details
        $this->requisition_no = $this->requisition->requisition_no;
        $this->file_no = $this->requisition->file_no;
        $this->item = $this->requisition->item;
        $this->source_of_funds = $this->requisition->source_of_funds;
        $this->date_assigned = $this->requisition->date_assigned;
        $this->date_sent_dps = $this->requisition->date_sent_dps;
        $this->ps_approval = $this->requisition->ps_approval;
        $this->vendor_name = $this->requisition->vendor_name;
        $this->amount = $this->requisition->amount;

        if ($this->date_sent_request_mof !== null && $this->request_no !== null && $this->release_no !== null && $this->release_date !== null) {
            $this->isEditing = false;
        }

        if ($this->cb_requisition->is_completed) {
            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        $this->logs = $this->requisition->statuslogs;
        return view('livewire.cost-budgeting-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function edit()
    {
        //Set dates to null if they are empty strings. This happens when the date is deleted
        if ($this->date_sent_request_mof === '') {
            $this->date_sent_request_mof = null;
        }

        if ($this->release_date === '') {
            $this->release_date = null;
        }

        $status = $this->requisition->requisition_status;

        //Get requisition status
        if (!$this->requisition->vote_control_requisition && !$this->cb_requisition->is_completed) {
            $status = $this->getStatus();
        }

        $this->validate(
            [
                'date_sent_request_mof' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                'release_date' => 'nullable|date|after:date_sent_request_mof',

            ],
            [
                'date_sent_request_mof.after_or_equal' => 'Please check date',
                'release_date.after' => 'The Release Date must be a date after the Date Sent to MoF',
            ]
        );

        $this->requisition->update([
            'requisition_status' => $status,
            'date_sent_request_mof' => $this->date_sent_request_mof,
            'request_category' => $this->request_category,
            'request_no' => trim($this->request_no),
            'release_type' => $this->release_type,
            'release_no' => trim($this->release_no),
            'release_date' => $this->release_date,
            'change_of_vote_no' => trim($this->change_of_vote_no),
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cb_requisition = $this->cb_requisition->fresh();
    }

    public function getFormattedDateAssigned()
    {
        if ($this->date_assigned) {
            return Carbon::parse($this->date_assigned)->format('F jS, Y');
        }
    }
    public function getFormattedDateSentPs()
    {
        if ($this->date_sent_dps) {
            return Carbon::parse($this->date_sent_dps)->format('F jS, Y');
        }
    }

    public function getDateSentCB()
    {
        if ($this->requisition->date_sent_cb) {
            return Carbon::parse($this->requisition->date_sent_cb)->format('F jS Y, h:i A');
        }
    }

    public function getFormattedDateSentMOF()
    {
        if ($this->date_sent_request_mof) {
            return Carbon::parse($this->date_sent_request_mof)->format('F jS, Y');
        }
    }

    public function getFormattedReleaseDate()
    {
        if ($this->release_date) {
            return Carbon::parse($this->release_date)->format('F jS, Y');
        }
    }

    public function getIsButtonDisabledProperty()
    {
        return $this->request_no === null || trim($this->request_no) === '' ||
            $this->release_no === null || trim($this->release_no) === '' ||
            $this->date_sent_request_mof === null || $this->date_sent_request_mof === '' ||
            $this->release_date === null || $this->release_date === '';
    }

    public function sendToProcurement()
    {

        $this->requisition->update([
            'requisition_status' => 'Received from Cost & Budgeting',
            'is_completed_cb' => true,
        ]);

        $this->cb_requisition->update([
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);

        //Send email to assigned procurement officer
        $user = $this->requisition->procurement_officer;
        if ($user) {
            Mail::to($user->email)->cc('maryann.basdeo@health.gov.tt')->queue(new CostBudgetingCompleted($this->requisition));
        } else {
            Mail::to('maryann.basdeo@health.gov.tt')->queue(new CostBudgetingCompleted($this->requisition));
        }

        return redirect()->route('cost_and_budgeting.index')->with('success', 'Requisition sent to procurement successfully');
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        $status = 'At Cost & Budgeting';

        if (!$this->date_sent_request_mof && !$this->request_no && !$this->release_no && !$this->release_date && !$this->change_of_vote_no) {
            $status = 'To be sent to MoF';
        }

        if ($this->date_sent_request_mof && !$this->release_no && !$this->release_date) {
            $status = 'Awaiting Release';
        }

        if ($this->date_sent_request_mof && $this->release_no && $this->release_date) {
            $status = 'To be sent to Procurement';
        }

        return $status;
    }

    public function updating($name, $value)
    {
        //Skip rendering when the change_of_vote_no is being updated
        if ($name === 'change_of_vote_no') {
            $this->skipRender();
        }
    }

    public function addLog()
    {

        $this->requisition->statuslogs()->create([
            'details' => $this->logdetails,
            'created_by' => Auth::user()->username,
        ]);

        $this->logdetails = null;

        $this->dispatch('close-log-modal');
        $this->dispatch('show-message', message: 'Log added successfully');
    }
}
