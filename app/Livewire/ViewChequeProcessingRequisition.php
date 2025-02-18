<?php

namespace App\Livewire;

use App\Mail\RequisitionCompleted;
use App\Models\ChequeProcessingRequisition;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
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
    public $vendors = [];

    public function render()
    {
        return view('livewire.view-cheque-processing-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->cp_requisition = ChequeProcessingRequisition::find($id);

        if (!$this->cp_requisition) {
            return abort(404);
        }

        $this->requisition = $this->cp_requisition->requisition;
        $this->vendors = $this->requisition->vendors()
            ->select(
                'id',
                'vendor_name',
                'amount',
                'date_cheque_processed',
                'change_of_vote_no',
                'cheque_no',
                'date_of_cheque',
                'date_sent_dispatch'
            )->get()->toArray();

        //Add accordion view to each vendor
        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'hide';
        }

        $this->date_cheque_processed = $this->requisition->date_cheque_processed;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->date_sent_dispatch = $this->requisition->date_sent_dispatch;

        foreach ($this->vendors as $vendor) {
            if (
                $vendor['date_cheque_processed'] === null ||
                $vendor['cheque_no'] === null ||
                $vendor['date_of_cheque'] === null ||
                $vendor['date_sent_dispatch'] === null
            ) {
                return; // Exit early if any field is null, keeping isEditing true
            }

            $this->isEditing = false;
        }

        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
            return;
        }
    }

    public function edit()
    {
        $this->validate([
            'vendors.*.date_cheque_processed' => 'nullable|date',
            'vendors.*.cheque_no' => 'nullable',
            'vendors.*.date_of_cheque' => 'nullable|date',
            'vendors.*.date_sent_dispatch' => 'nullable|date',
        ]);

        foreach ($this->vendors as &$vendor) {
            if ($vendor['date_cheque_processed'] === "") {
                $vendor['date_cheque_processed'] = null;
            }

            if ($vendor['date_of_cheque'] === "") {
                $vendor['date_of_cheque'] = null;
            }

            if ($vendor['date_sent_dispatch'] === "") {
                $vendor['date_sent_dispatch'] = null;
            }
        }

        unset($vendor);

        $status = $this->getStatus();

        foreach ($this->vendors as $vendor) {
            $this->requisition->vendors()->where('id', $vendor['id'])->update([
                'date_cheque_processed' => $vendor['date_cheque_processed'],
                'cheque_no' => $vendor['cheque_no'],
                'date_of_cheque' => $vendor['date_of_cheque'],
                'date_sent_dispatch' => $vendor['date_sent_dispatch'],
            ]);
        }

        $this->requisition->update([
            'requisition_status' => $status,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cp_requisition = $this->cp_requisition->fresh();
    }

    public function completeRequisition()
    {
        $this->cp_requisition->update([
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);

        $this->requisition->update([
            'requisition_status' => 'Completed',
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);

        // $assigned_to = $this->requisition->procurement_officer;
        // if ($assigned_to) {
        //     Mail::to($assigned_to->email)->cc('maryann.basdeo@health.gov.tt')->queue(new RequisitionCompleted($this->requisition));
        // } else {
        //     Mail::to('maryann.basdeo@health.gov.tt')->queue(new RequisitionCompleted($this->requisition));
        // }

        return redirect()->route('cheque_processing.index')->with('success', 'Requisition completed successfully');
    }

    public function getIsButtonDisabledProperty()
    {
        foreach ($this->vendors as $vendor) {
            if (
                empty($vendor['date_cheque_processed']) ||
                empty($vendor['cheque_no']) ||
                empty($vendor['date_of_cheque']) ||
                empty($vendor['date_sent_dispatch'])
            ) {
                return true; // Disable the button if any vendor has missing data
            }
        }

        return false; // Enable the button only if all vendors have the required fields
    }


    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        // Define priority levels for sorting (lower value = earlier stage)
        $priority = [
            'At Cheque Processing' => 1,
            'Cheque Details to be Entered' => 2,
            'To be Sent to Cheque Dispatch' => 3,
            'To Be Completed by Cheque Processing' => 4,
        ];

        $statuses = collect($this->vendors)->map(function ($vendor) {
            if ($vendor['date_cheque_processed'] && $vendor['cheque_no'] && $vendor['date_of_cheque'] && !$vendor['date_sent_dispatch']) {
                return 'To be Sent to Cheque Dispatch';
            }
            if ($vendor['date_cheque_processed'] && $vendor['cheque_no'] && $vendor['date_of_cheque'] && $vendor['date_sent_dispatch']) {
                return 'To Be Completed by Cheque Processing';
            }

            return 'At Cheque Processing';
        });

        return $statuses->sortBy(fn($status) => $priority[$status])->first() ?? 'At Cheque Processing';
    }


    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
