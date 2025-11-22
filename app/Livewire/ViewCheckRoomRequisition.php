<?php

namespace App\Livewire;

use App\Models\CheckRoomRequisition;
use App\Models\User;
use App\Notifications\NotifyChequeProcessing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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

    public $vendors = [];
    public $isEditing = true;

    public function render()
    {
        return view('livewire.view-check-room-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->cr_requisition = CheckRoomRequisition::find($id);

        if (!$this->cr_requisition) {
            return abort(404);
        }

        $this->requisition = $this->cr_requisition->requisition;
        $this->vendors = $this->requisition->vendors()
            ->select(
                'id',
                'vendor_name',
                'amount',
                'change_of_vote_no',
                'date_received_from_vc',
                'voucher_destination',
                'date_sent_audit',
                'date_received_from_audit',
                'date_sent_chequeprocessing'
            )
            ->get()
            ->toArray();

        //Add accordion view to each vendor
        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'hide';
        }

        $this->date_received_from_vc = $this->requisition->date_received_from_vc;
        $this->voucher_destination = $this->requisition->voucher_destination;
        $this->date_sent_audit = $this->requisition->date_sent_audit;
        $this->date_received_from_audit = $this->requisition->date_received_from_audit;
        $this->date_sent_chequeprocessing = $this->requisition->date_sent_chequeprocessing;

        foreach ($this->vendors as $vendor) {
            if ($vendor['date_received_from_vc'] !== null && $vendor['voucher_destination'] !== null && $vendor['date_sent_chequeprocessing'] !== null) {
                $this->isEditing = false;
                break;
            }
        }


        if (Auth::user()->role->name === 'Viewer') {
            $this->isEditing = false;
        }
    }

    public function edit()
    {
        foreach ($this->vendors as &$vendor) {
            if ($vendor['date_received_from_vc'] === "") {
                $vendor['date_received_from_vc'] = null;
            }

            if ($vendor['date_sent_audit'] === "") {
                $vendor['date_sent_audit'] = null;
            }

            if ($vendor['date_received_from_audit'] === "") {
                $vendor['date_received_from_audit'] = null;
            }

            if ($vendor['date_sent_chequeprocessing'] === "") {
                $vendor['date_sent_chequeprocessing'] = null;
            }

            if ($vendor['voucher_destination'] === 'Cheque Processing') {
                $vendor['date_sent_audit'] = null;
                $vendor['date_received_from_audit'] = null;
            }
        }

        unset($vendor);

        $this->validate(
            [
                'vendors.*.date_received_from_vc' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                'vendors.*.date_sent_audit' => 'nullable|date|after_or_equal:vendors.*.date_received_from_vc',
                'vendors.*.date_received_from_audit' => 'nullable|date|after_or_equal:vendors.*.date_sent_audit',
                'vendors.*.date_sent_chequeprocessing' => 'nullable|date|after_or_equal:vendors.*.date_received_from_audit',
            ],
            [
                'vendors.*.date_received_from_vc.after_or_equal' => 'Please check date.',
                'vendors.*.date_sent_audit.after_or_equal' => 'The Date Sent to Audit must be a date after or equal to the Date Received from Vote Control.',
                'vendors.*.date_received_from_audit.after_or_equal' => 'The Date Received from Audit must be a date after or equal to the Date Sent to Audit.',
                'vendors.*.date_sent_chequeprocessing.after_or_equal' => 'The Date Sent to Cheque Processing must be a date after or equal to the Date Received from Audit.',
            ]
        );

        $status = $this->getStatus();

        foreach ($this->vendors as $vendor) {
            $this->requisition->vendors()->where('id', $vendor['id'])->update([
                'date_received_from_vc' => $vendor['date_received_from_vc'],
                'voucher_destination' => $vendor['voucher_destination'],
                'date_sent_audit' => $vendor['date_sent_audit'],
                'date_received_from_audit' => $vendor['date_received_from_audit'],
                'date_sent_chequeprocessing' => $vendor['date_sent_chequeprocessing'],
            ]);
        }

        $this->requisition->update([
            'requisition_status' => $status,
        ]);

        $this->isEditing = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->cr_requisition = $this->cr_requisition->fresh();
    }

    public function getIsButtonDisabledProperty()
    {
        foreach ($this->vendors as $vendor) {
            if ($vendor['voucher_destination'] === 'Internal Audit') {
                if (
                    empty($vendor['date_received_from_vc']) ||
                    empty($vendor['voucher_destination']) ||
                    empty($vendor['date_sent_audit']) ||
                    empty($vendor['date_received_from_audit']) ||
                    empty($vendor['date_sent_chequeprocessing'])
                ) {
                    return true; // Disable the button if any required field is missing
                }
            } else {
                if (
                    empty($vendor['date_received_from_vc']) ||
                    empty($vendor['voucher_destination']) ||
                    empty($vendor['date_sent_chequeprocessing'])
                ) {
                    return true; // Disable the button if any required field is missing
                }
            }
        }

        return false; // Enable the button only if all vendors have the required fields
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        // Define priority levels for sorting (lower value = earlier stage)
        $priority = [
            'At Check Staff' => 1,
            'Received by Check Staff' => 2,
            'To be Sent to Internal Audit' => 3,
            'Sent to Internal Audit' => 4,
            'To Be Sent to Cheque Processing' => 5,
        ];

        $statuses = collect($this->vendors)->map(function ($vendor) {
            if ($vendor['date_received_from_vc'] && $vendor['voucher_destination'] === 'Internal Audit') {
                if (!$vendor['date_sent_audit']) {
                    return 'To be Sent to Internal Audit';
                }
                if ($vendor['date_sent_audit'] && !$vendor['date_received_from_audit']) {
                    return 'Sent to Internal Audit';
                }
                if ($vendor['date_sent_audit'] && $vendor['date_received_from_audit']) {
                    return 'To Be Sent to Cheque Processing';
                }
            }
            if ($vendor['date_received_from_vc'] && $vendor['voucher_destination'] === 'Cheque Processing' && $vendor['date_sent_chequeprocessing']) {
                return 'To Be Sent to Cheque Processing';
            }

            return 'At Check Staff';
        });

        return $statuses->sortBy(fn($status) => $priority[$status])->first() ?? 'At Check Staff';
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

        //Get Cheque Processing Staff
        $chequeProcessingStaff = User::chequeProcessing()->get();
        foreach ($chequeProcessingStaff as $staff) {
            Notification::send($staff, new NotifyChequeProcessing($this->requisition));
        }

        return redirect()->route('check_room.index')->with('success', 'Requisition sent to Cheque Processing successfully');
    }

    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
