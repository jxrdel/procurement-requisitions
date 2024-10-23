<?php

namespace App\Livewire;

use App\Models\AccountsRequisition;
use App\Models\Requisition;
use Carbon\Carbon;
use Livewire\Component;

class ViewAccountsRequisition extends Component
{
    public $requisition;
    public $accounts_requisition;

    public $date_sent_chequeroom;
    public $date_of_cheque;
    public $cheque_no;
    public $date_cheque_forwarded;

    public $isEditing = true;

    public function mount($id)
    {
        $this->accounts_requisition = AccountsRequisition::find($id);
        $this->requisition = $this->accounts_requisition->requisition;

        $this->date_sent_chequeroom = $this->requisition->date_sent_chequeroom;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_cheque_forwarded = $this->requisition->date_cheque_forwarded;

        if ($this->accounts_requisition->is_completed) {
            $this->isEditing = false;
        }
    }

    public function render()
    {
        return view('livewire.view-accounts-requisition')->title($this->requisition->item . ' | View Requisition');
    }

    public function getFormattedDateSentChequeroom(){
        if($this->date_sent_chequeroom){
            return Carbon::parse($this->date_sent_chequeroom)->format('F jS, Y');
        }
    }

    public function getFormattedDateOfCheque(){
        if($this->date_of_cheque){
            return Carbon::parse($this->date_of_cheque)->format('F jS, Y');
        }
    }

    public function getFormattedDateChequeForwarded(){
        if($this->date_cheque_forwarded){
            return Carbon::parse($this->date_cheque_forwarded)->format('F jS, Y');
        }
    }
    public function getIsButtonDisabledProperty()
    {
        return $this->date_sent_chequeroom === null || $this->date_sent_chequeroom === '' ||
            $this->date_of_cheque === null || $this->date_of_cheque === '' ||
            $this->cheque_no === null || trim($this->cheque_no) === '' ||
            $this->date_cheque_forwarded === null || $this->date_cheque_forwarded === '';
    }

    //Edit funciton

    public function edit(){
        $this->requisition->update([
            'requistion_status' => 'Completed',
            'date_sent_chequeroom' => $this->date_sent_chequeroom,
            'date_of_cheque' => $this->date_of_cheque,
            'cheque_no' => $this->cheque_no,
            'date_cheque_forwarded' => $this->date_cheque_forwarded,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->accounts_requisition = $this->accounts_requisition->fresh();
    }

    public function completeRequisition(){
        $this->accounts_requisition->update([
            'is_completed' => true,
        ]);

        $this->requisition->update([
            'requisition_status' => 'Completed',
            'is_completed' => true,
        ]);

        return redirect()->route('accounts_requisitions.index')->with('success', 'Requisition completed successfully');
    }

}
