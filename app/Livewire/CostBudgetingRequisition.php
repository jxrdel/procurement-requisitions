<?php

namespace App\Livewire;

use App\Mail\CostBudgetingCompleted;
use App\Mail\ErrorNotification;
use App\Models\CBRequisition;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Vote;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
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
    public $total;

    public $isEditing = true;
    public $votes;
    public $vendors = [];

    public function mount($id)
    {
        $this->cb_requisition = CBRequisition::find($id);
        $this->votes = Vote::active()->get();

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
        $this->loadVendors();
        $this->total = $this->requisition->vendors()->sum('amount');

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
        $this->isEditing = false;

        foreach ($this->vendors as $vendor) {
            if ($vendor['date_sent_request_mof'] === null || $vendor['request_no'] === null || $vendor['release_no'] === null || $vendor['release_date'] === null) {
                $this->isEditing = true;
                break;
            }
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
        return view('livewire.cost-budgeting-requisition')->title($this->requisition->requisition_no . ' | View Requisition');
    }

    public function edit()
    {
        //Set dates to null if they are empty strings. This happens when the date is deleted
        foreach ($this->vendors as &$vendor) { //Use reference to update the original array
            if ($vendor['date_sent_request_mof'] === '') {
                $vendor['date_sent_request_mof'] = null;
            }

            if ($vendor['release_date'] === '') {
                $vendor['release_date'] = null;
            }
        }

        // Unset reference to avoid unintended behavior
        unset($vendor);

        $this->validate(
            [
                'vendors.*.date_sent_request_mof' => 'nullable|date|date_format:Y-m-d|after_or_equal:' . $this->requisition->date_sent_dps,
                'vendors.*.release_date' => 'nullable|date|date_format:Y-m-d|after:vendors.*.date_sent_request_mof',

            ],
            [
                'vendors.*.date_sent_request_mof.after_or_equal' => 'Please check date',
                'vendors.*.release_date.after' => 'The Release Date must be a date after the Date Sent to MoF',
            ]
        );

        $status = $this->requisition->requisition_status;

        try {
            //Get requisition status
            if (!$this->requisition->vote_control_requisition && !$this->cb_requisition->is_completed) {
                $status = $this->getStatus();
            }

            $this->requisition->update([
                'requisition_status' => $status,
            ]);

            foreach ($this->vendors as $vendor) {
                $this->requisition->vendors()->where('id', $vendor['id'])->update([
                    'date_sent_request_mof' => $vendor['date_sent_request_mof'],
                    'request_category' => $vendor['request_category'],
                    'request_no' => $vendor['request_no'],
                    'release_type' => $vendor['release_type'],
                    'release_no' => $vendor['release_no'],
                    'release_date' => $vendor['release_date'],
                ]);
                $this->requisition->vendors()->find($vendor['id'])->votes()->sync($vendor['selected_votes']);
            }

            Log::info('Cost & Budgeting Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->username);
            $this->isEditing = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Record edited successfully');
            $this->requisition = $this->requisition->fresh();
            $this->cb_requisition = $this->cb_requisition->fresh();
            $this->loadVendors();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition in Cost & Budgeting: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
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
        foreach ($this->vendors as $vendor) {
            // check for null AND empty string
            if ($vendor['date_sent_request_mof'] === null || $vendor['request_no'] === null || $vendor['release_no'] === null || $vendor['release_date'] === null || $vendor['date_sent_request_mof'] === '' || $vendor['request_no'] === '' || $vendor['release_no'] === '' || $vendor['release_date'] === '') {
                return true;
            }
        }

        return false;
    }

    public function sendToProcurement()
    {

        $this->requisition->update([
            'requisition_status' => 'Sent to Procurement',
            'is_completed_cb' => true,
        ]);

        $this->cb_requisition->update([
            'is_completed' => true,
            'date_completed' => Carbon::now(),
        ]);

        $this->requisition->statusLogs()->create([
            'details' => 'Sent from Cost & Budgeting to Procurement by ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);

        foreach ($this->vendors as $vendor) {
            $this->requisition->vendors()->where('id', $vendor['id'])->update([
                'vendor_status' => 'Sent to Procurement',
            ]);
        }

        Log::info('Cost & Budgeting Requisition #' . $this->requisition->requisition_no . ' was sent to Procurement by ' . Auth::user()->username);

        //Send email to assigned procurement officer
        $assigned_to = $this->requisition->procurement_officer;
        $maryann = User::where('name', 'Maryann Basdeo')->first();

        $recipients = collect([$assigned_to, $maryann])
            ->filter()
            ->unique('id');

        Notification::send($recipients, new \App\Notifications\CostBudgetingCompleted($this->requisition));

        return redirect()->route('queue')->with('success', 'Sent to procurement successfully');
    }

    public function getStatus()
    {
        if ($this->requisition->is_completed) {
            return 'Completed';
        }

        $statuses = [];

        foreach ($this->vendors as $vendor) {
            if (!$vendor['date_sent_request_mof'] && !$vendor['request_no'] && !$vendor['release_no'] && !$vendor['release_date']) {
                $statuses[] = 'Sent to Cost & Budgeting';
            } elseif ($vendor['date_sent_request_mof'] && !$vendor['release_no'] && !$vendor['release_date']) {
                $statuses[] = 'Awaiting Release';
            } elseif ($vendor['date_sent_request_mof'] && $vendor['release_no'] && $vendor['release_date']) {
                $statuses[] = 'To be sent to Procurement';
            } else {
                $statuses[] = 'At Cost & Budgeting';
            }
        }

        if (empty($statuses)) {
            return 'At Cost & Budgeting'; // Default status if no vendors exist
        }

        // Define priority order
        $priority = [
            'Sent to Cost & Budgeting' => 1,
            'Awaiting Release' => 2,
            'To be sent to Procurement' => 3,
            'At Cost & Budgeting' => 4,
        ];

        // Get the lowest priority status
        return collect($statuses)->sortBy(fn($status) => $priority[$status])->first();
    }


    public function updating($name, $value)
    {
        if (str_starts_with($name, 'vendors') && str_ends_with($name, 'selected_votes')) {
            $this->skipRender();
        }
    }

    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }

    public function getFormattedDate($date)
    {
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    private function loadVendors()
    {
        $this->vendors = $this->requisition->vendors()
            ->with('votes')
            ->select(
                'id',
                'vendor_name',
                'amount',
                'date_sent_request_mof',
                'request_category',
                'request_no',
                'release_type',
                'release_no',
                'release_date',
            )
            ->get()->toArray();
        //Add accordion view to each vendor
        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'show';
            $this->vendors[$key]['selected_votes'] = $this->requisition->vendors()->find($vendor['id'])->votes()->pluck('vote_id')->toArray();
        }
    }

    public function getTotalAmountProperty()
    {
        return collect($this->requisition->vendors)->sum('amount');
    }
}
