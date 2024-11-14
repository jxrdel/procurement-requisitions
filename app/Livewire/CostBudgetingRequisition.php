<?php

namespace App\Livewire;

use App\Mail\CostBudgetingCompleted;
use App\Models\CBRequisition;
use App\Models\Requisition;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CostBudgetingRequisition extends Component
{
    public $cb_requisition;
    public $requisition;

    public $date_sent_request_mof;
    public $request_no;
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

    public $isEditing = true;

    public function mount($id)
    {
        $this->cb_requisition = CBRequisition::find($id);
        $this->requisition = Requisition::find($this->cb_requisition->requisition_id);

        $this->date_sent_request_mof = $this->requisition->date_sent_request_mof;
        $this->request_no = $this->requisition->request_no;
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
    }

    public function render()
    {
        return view('livewire.cost-budgeting-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function edit()
    {

        if ($this->date_sent_request_mof === '') {
            $this->date_sent_request_mof = null;
        }

        if ($this->release_date === '') {
            $this->release_date = null;
        }

        $status = $this->requisition->requisition_status;

        if (!$this->requisition->vote_control_requisition && !$this->cb_requisition->is_completed) {
            $status = $this->getStatus();
        }

        $this->validate(
            [
                'date_sent_request_mof' => 'nullable|date|after_or_equal:' . $this->cb_requisition->date_received,
                'release_date' => 'nullable|date|after:date_sent_request_mof',

            ],
            [
                'date_sent_request_mof.after_or_equal' => 'The Date Sent to MoF must be a date after the Date Sent to Cost & Budgeting',
                'release_date.after' => 'The Release Date must be a date after the Date Sent to MoF',
            ]
        );

        $this->requisition->update([
            'requisition_status' => $status,
            'date_sent_request_mof' => $this->date_sent_request_mof,
            'request_no' => trim($this->request_no),
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

        // Mail::to('jardel.regis@health.gov.tt')->send(new CostBudgetingCompleted($this->requisition));

        return redirect()->route('cost_and_budgeting.index')->with('success', 'Requisition sent to procurement successfully');
    }

    public function getStatus()
    {
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
}
