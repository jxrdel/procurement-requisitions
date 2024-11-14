<?php

namespace App\Livewire;

use App\Models\ChequeProcessingRequisition;
use Carbon\Carbon;
use Livewire\Component;

class ViewChequeProcessingRequisition extends Component
{
    public $requisition;
    public $cp_requisition;

    public $date_cheque_processed;
    public $cheque_no;
    public $date_of_cheque;
    public $date_sent_dispatch;

    public $isEditing = true;

    public function render()
    {
        return view('livewire.view-cheque-processing-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->cp_requisition = ChequeProcessingRequisition::find($id);
        $this->requisition = $this->cp_requisition->requisition;

        $this->date_cheque_processed = $this->requisition->date_cheque_processed;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->date_sent_dispatch = $this->requisition->date_sent_dispatch;

        if ($this->date_cheque_processed !== null && $this->cheque_no !== null && $this->date_of_cheque !== null && $this->date_sent_dispatch !== null) {
            $this->isEditing = false;
        }
    }

    public function edit()
    {
        $this->validate([
            'date_cheque_processed' => 'nullable|date',
            'date_of_cheque' => 'nullable|date',
            'date_sent_dispatch' => 'nullable|date',
        ]);

        if ($this->date_cheque_processed === '') {
            $this->date_cheque_processed = null;
        }

        if ($this->date_of_cheque === '') {
            $this->date_of_cheque = null;
        }

        if ($this->date_sent_dispatch === '') {
            $this->date_sent_dispatch = null;
        }

        $status = $this->getStatus();

        $this->requisition->update([
            'requisition_status' => $status,
            'date_cheque_processed' => $this->date_cheque_processed,
            'cheque_no' => $this->cheque_no,
            'date_of_cheque' => $this->date_of_cheque,
            'date_sent_dispatch' => $this->date_sent_dispatch,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cp_requisition = $this->cp_requisition->fresh();
    }

    public function getIsButtonDisabledProperty()
    {
        return $this->date_cheque_processed === null || $this->cheque_no === null || $this->date_of_cheque === null || $this->date_sent_dispatch === null;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function getStatus()
    {
        $status = 'At Cheque Processing';

        if ($this->date_cheque_processed && !$this->cp_requisition->is_completed) {
            $status = 'Cheque Details to be Entered';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && !$this->date_sent_dispatch) {
            $status = 'To be Sent to Cheque Dispatch';
        }

        if ($this->date_cheque_processed && $this->cheque_no && $this->date_of_cheque && $this->date_sent_dispatch) {
            $status = 'To Be Completed by Cheque Dispatch';
        }

        return $status;
    }
}
