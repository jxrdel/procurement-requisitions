<?php

namespace App\Livewire;

use App\Mail\NotifyVoteControl;
use App\Models\APRequisition;
use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ViewAccountsPayableRequisition extends Component
{
    public $requisition;
    public $ap_requisition;

    public $date_received_ap;
    public $date_sent_vc;

    public $vendors = [];
    public $isEditing = true;

    public function mount($id)
    {
        $this->ap_requisition = APRequisition::find($id);

        if (!$this->ap_requisition) {
            return abort(404);
        }

        $this->requisition = $this->ap_requisition->requisition;
        $this->vendors = $this->requisition->vendors()->select('id', 'vendor_name', 'amount', 'date_received_ap', 'date_sent_vc')->get()->toArray();

        //Add accordion view to each vendor
        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'hide';
        }


        $this->date_received_ap = $this->requisition->date_received_ap;
        $this->date_sent_vc = $this->requisition->date_sent_vc;

        foreach ($this->vendors as $vendor) {
            if ($vendor['date_received_ap'] !== null && $vendor['date_sent_vc'] !== null) {
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
        return view('livewire.view-accounts-payable-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function edit()
    {
        foreach ($this->vendors as &$vendor) {
            if ($vendor['date_received_ap'] == '') {
                $vendor['date_received_ap'] = null;
            }

            if ($vendor['date_sent_vc'] == '') {
                $vendor['date_sent_vc'] = null;
            }
        }

        unset($vendor);

        $this->validate(
            [
                'vendors.*.date_received_ap' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
                'vendors.*.date_sent_vc' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
            ],
            [
                'vendors.*.date_received_ap.after_or_equal' => 'Please check date',
                'vendors.*.date_sent_vc.after_or_equal' => 'Please check date',
            ]
        );

        $status = $this->getStatus();

        $this->requisition->update([
            'requisition_status' => $status,
        ]);

        foreach ($this->vendors as $vendor) {
            $this->requisition->vendors()->where('id', $vendor['id'])->update([
                'date_received_ap' => $vendor['date_received_ap'],
                'date_sent_vc' => $vendor['date_sent_vc'],
            ]);
        }

        $this->isEditing = false;
        $this->dispatch('show-message', message: 'Record edited successfully');
        $this->requisition = $this->requisition->fresh();
        $this->ap_requisition = $this->ap_requisition->fresh();
    }

    public function getIsButtonDisabledProperty()
    {
        foreach ($this->vendors as $vendor) {
            if ($vendor['date_received_ap'] === null || $vendor['date_sent_vc'] === null) {
                return true;
            }
        }

        return false;
        // return $this->date_received_ap === null || $this->date_sent_vc === null;
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function sendToVoteControl()
    {

        $this->requisition->update([
            'requisition_status' => 'Sent to Vote Control',
            'updated_by' => Auth::user()->username,
        ]);

        $this->ap_requisition->update([
            'is_completed' => true,
            'date_completed' => now(),
        ]);

        $this->requisition->vote_control_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        //Send email to Vote Control

        //Get Vote Control users
        $users = User::voteControl()->get();

        foreach ($users as $user) {
            // Mail::to($user->email)->queue(new NotifyVoteControl($this->requisition));
        }

        return redirect()->route('accounts_payable.index')->with('success', 'Requisition sent to Vote Control successfully');
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        // Define priority levels for sorting (lower value = earlier stage)
        $priority = [
            'At Accounts Payable' => 1,
            'To Be Sent to Vote Control' => 2,
        ];

        $statuses = collect($this->vendors)->map(function ($vendor) {
            if ($vendor['date_received_ap'] && $vendor['date_sent_vc'] && !$this->ap_requisition->is_completed) {
                return 'To Be Sent to Vote Control';
            }
            return 'At Accounts Payable';
        });

        return $statuses->sortBy(fn($status) => $priority[$status])->first() ?? 'At Accounts Payable';
    }


    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
}
