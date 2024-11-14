<?php

namespace App\Livewire;

use App\Models\CheckRoomRequisition;
use Carbon\Carbon;
use Livewire\Component;

class ViewCheckRoomRequisition extends Component
{
    public $requisition;
    public $cr_requisition;

    public $date_received_from_vc;
    public $voucher_destination;
    public $date_sent_audit;
    public $date_received_from_audit;
    public $date_sent_chequeprocessing;

    public $isEditing = true;

    public function render()
    {
        return view('livewire.view-check-room-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->cr_requisition = CheckRoomRequisition::find($id);
        $this->requisition = $this->cr_requisition->requisition;

        $this->date_received_from_vc = $this->requisition->date_received_from_vc;
        $this->voucher_destination = $this->requisition->voucher_destination;
        $this->date_sent_audit = $this->requisition->date_sent_audit;
        $this->date_received_from_audit = $this->requisition->date_received_from_audit;
        $this->date_sent_chequeprocessing = $this->requisition->date_sent_chequeprocessing;

        if ($this->date_received_from_vc !== null && $this->voucher_destination !== null && $this->date_sent_chequeprocessing !== null) {
            $this->isEditing = false;
        }
    }

    public function edit()
    {
        if ($this->date_received_from_vc === '') {
            $this->date_received_from_vc = null;
        }

        if ($this->date_sent_audit === '') {
            $this->date_sent_audit = null;
        }

        if ($this->date_received_from_audit === '') {
            $this->date_received_from_audit = null;
        }

        if ($this->date_sent_chequeprocessing === '') {
            $this->date_sent_chequeprocessing = null;
        }

        if ($this->voucher_destination === 'Cheque Processing') {
            $this->date_sent_audit = null;
            $this->date_received_from_audit = null;
        }

        $this->validate(
            [
                'date_received_from_vc' => 'nullable|date|after_or_equal:' . $this->requisition->check_room_requisition->date_received,
                'date_sent_audit' => 'nullable|date|after_or_equal:date_received_from_vc',
                'date_received_from_audit' => 'nullable|date|after_or_equal:date_sent_audit',
                'date_sent_chequeprocessing' => 'nullable|date|after_or_equal:date_received_from_audit',
            ],
            [
                'date_received_from_vc.after_or_equal' => 'This date must be after the date the requisition was sent to Check Staff.',
                'date_sent_audit.after_or_equal' => 'The Date Sent to Audit must be a date after or equal to the Date Received from Vote Control.',
                'date_received_from_audit.after_or_equal' => 'The Date Received from Audit must be a date after or equal to the Date Sent to Audit.',
                'date_sent_chequeprocessing.after_or_equal' => 'The Date Sent to Cheque Processing must be a date after or equal to the Date Received from Audit.',
            ]
        );

        $status = $this->getStatus();

        $this->requisition->update([
            'requisition_status' => $status,
            'date_received_from_vc' => $this->date_received_from_vc,
            'voucher_destination' => $this->voucher_destination,
            'date_sent_audit' => $this->date_sent_audit,
            'date_received_from_audit' => $this->date_received_from_audit,
            'date_sent_chequeprocessing' => $this->date_sent_chequeprocessing,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cr_requisition = $this->cr_requisition->fresh();
    }

    public function getIsButtonDisabledProperty()
    {
        $isDisabled = true;

        if ($this->voucher_destination === 'Internal Audit') {
            $isDisabled = $this->date_received_from_vc === null || trim($this->date_received_from_vc) === '' ||
                $this->voucher_destination === null || trim($this->voucher_destination) === '' ||
                $this->date_sent_audit === null || trim($this->date_sent_audit) === '' ||
                $this->date_received_from_audit === null || trim($this->date_received_from_audit) === '' ||
                $this->date_sent_chequeprocessing === null || trim($this->date_sent_chequeprocessing) === '';
        } else {
            $isDisabled = $this->date_received_from_vc === null || trim($this->date_received_from_vc) === '' ||
                $this->voucher_destination === null || trim($this->voucher_destination) === '' ||
                $this->date_sent_chequeprocessing === null || trim($this->date_sent_chequeprocessing) === '';
        }
        return $isDisabled;
    }

    public function getStatus()
    {
        $status = 'At Check Room';

        if ($this->date_received_from_vc && !$this->cr_requisition->is_completed) {
            $status = 'Received by Check Room';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && !$this->date_sent_audit) {
            $status = 'To be Sent to Internal Audit';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && $this->date_sent_audit && !$this->date_received_from_audit) {
            $status = 'Sent to Internal Audit';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Internal Audit' && $this->date_sent_audit && $this->date_received_from_audit && !$this->date_sent_chequeprocessing) {
            $status = 'To Be Sent to Cheque Processing';
        }

        if ($this->date_received_from_vc && $this->voucher_destination === 'Cheque Processing' && $this->date_sent_chequeprocessing) {
            $status = 'To Be Sent to Cheque Processing';
        }

        return $status;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function sendToChequeProcessing()
    {
        $this->cr_requisition->update([
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);

        $this->requisition->update([
            'requisition_status' => 'Sent to Cheque Processing',
        ]);

        $this->requisition->cheque_processing_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        return redirect()->route('check_room.index')->with('success', 'Requisition sent to Cheque Processing successfully');
    }
}
