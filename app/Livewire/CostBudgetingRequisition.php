<?php

namespace App\Livewire;

use App\Models\CBRequisition;
use App\Models\Requisition;
use Carbon\Carbon;
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

        if ($this->cb_requisition->is_completed) {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.cost-budgeting-requisition')->title($this->requisition->item . ' | View Requisition');
    }

    public function edit(){
        
        $this->requisition->update([
            'date_sent_request_mof' => $this->date_sent_request_mof,
            'request_no' => $this->request_no,
            'release_no' => $this->release_no,
            'release_date' => $this->release_date,
            'change_of_vote_no' => $this->change_of_vote_no,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cb_requisition = $this->cb_requisition->fresh();
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

    public function sendToProcurement(){
        $this->requisition->update([
            'requisition_status' => 'Sent to Procurement',
        ]);

        $this->cb_requisition->update([
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);
        // $this->cb_requisition = $this->cb_requisition->fresh();

        return redirect()->route('cost_and_budgeting.index')->with('success', 'Requisition sent to procurement successfully');
    }
}
