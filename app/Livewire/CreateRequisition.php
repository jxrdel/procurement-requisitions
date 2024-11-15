<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Requisition;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateRequisition extends Component
{
    use WithFileUploads;

    #[Title('Create Requsition')]

    public $requisition_status;
    public $requisition_no;
    public $requesting_unit;
    public $file_no;
    public $item;
    public $source_of_funds;
    public $assigned_to;
    public $date_assigned;
    public $date_sent_dps;
    public $ps_approval = 'Not Sent';
    public $ps_approval_date;
    public $vendor_name;
    public $amount;
    public $denied_note;
    public $sent_to_cb;
    public $date_sent_cb;
    public $uploads;

    public $logdetails;

    public $departments;
    public $staff;
    public $logs = [];
    public $votes;

    public function mount()
    {
        $this->requisition_no = Requisition::generateRequisitionNo();
        $this->departments = Department::all();
        $this->staff = User::procurement()->get();
        $this->votes = Vote::all();
    }


    public function render()
    {
        return view('livewire.create-requisition');
    }

    public function save()
    {

        if ($this->date_assigned === '') {
            $this->date_assigned = null;
        }

        if ($this->date_sent_dps === '') {
            $this->date_sent_dps = null;
        }

        if (!$this->validateForm()) {
            return;  // Stop execution if form validation fails
        }

        if ($this->ps_approval === 'Not Sent') {
            $this->requisition_status = 'To be Sent to DPS';
        }

        if ($this->ps_approval === 'Pending') {
            $this->requisition_status = 'Pending PS Approval';
        }

        if ($this->ps_approval === 'Approval Denied') {
            $this->requisition_status = 'Denied by PS';
        }

        if ($this->ps_approval === 'Approved') {
            $this->requisition_status = 'Approved by PS';
        }


        $newrequisition = Requisition::create([
            'requisition_status' => $this->requisition_status,
            'requisition_no' => Requisition::generateRequisitionNo(),
            'requesting_unit' => $this->requesting_unit,
            'file_no' => $this->file_no,
            'item' => $this->item,
            'source_of_funds' => $this->source_of_funds,
            'assigned_to' => $this->assigned_to,
            'date_assigned' => $this->date_assigned,
            'date_sent_dps' => $this->date_sent_dps,
            'ps_approval' => $this->ps_approval,
            'ps_approval_date' => $this->ps_approval_date,
            'vendor_name' => $this->vendor_name,
            'amount' => $this->amount,
            'denied_note' => $this->denied_note,
            'created_by' => Auth::user()->username,
        ]);

        foreach ($this->logs as $log) {
            $newrequisition->statuslogs()->create([
                'details' => $log,
                'created_by' => Auth::user()->username,
            ]);
        }


        if (!is_null($this->uploads)) {
            foreach ($this->uploads as $photo) {
                $filename = $photo->getClientOriginalName();
                $path = $photo->store('file_uploads', 'public');
                $newrequisition->file_uploads()->create([
                    'file_name' => $filename,
                    'file_path' => $path,
                    // 'uploaded_by' => auth()->user()->username,
                ]);
            }
        }

        return redirect()->route('requisitions.index')->with('success', 'Requisition created successfully');
    }

    public function validateForm()
    {

        $reqvalidator = Validator::make([
            'requisition_no' => $this->requisition_no,
            'requesting_unit' => $this->requesting_unit,
            'file_no' => $this->file_no,
            'item' => $this->item,
            'assigned_to' => $this->assigned_to,
            'date_sent_dps' => $this->date_sent_dps,
            'ps_approval' => $this->ps_approval,
            // 'sent_to_cb' => $this->sent_to_cb,
            // 'date_sent_cb' => $this->date_sent_cb,
        ], [
            'requisition_no' => 'required|unique:requisitions',
            'requesting_unit' => 'required',
            'file_no' => 'required',
            'item' => 'required',
            'assigned_to' => 'required',
            'date_sent_dps' => 'nullable|date|before_or_equal:today',
            // 'date_sent_cb' => 'nullable|sometimes|after:date_sent_dps',
        ])
            ->after(function ($validator) {
                // Ensure 'ps_approval' is not 'Not Sent' if 'date_sent_dps' is populated
                if ($this->date_sent_dps !== null && $this->ps_approval === 'Not Sent') {
                    $validator->errors()->add('ps_approval', 'PS approval cannot be "Not Sent" if the Date sent to DPS is populated.');
                }

                // Ensure 'date_sent_dps' is not null if 'ps_approval' is not 'Not Sent'
                if ($this->ps_approval !== 'Not Sent' && $this->date_sent_dps === null) {
                    $validator->errors()->add('date_sent_dps', 'Date sent to DPS cannot be null if PS Approval is set to ' . $this->ps_approval);
                }

                // Ensure 'date_sent_cb' is not null if 'sent_to_cb' is "Yes"
                // if ($this->sent_to_cb === 'Yes' && $this->date_sent_cb === null) {
                //     $validator->errors()->add('date_sent_cb', 'Date sent to DFA cannot be null if sent to DFA is "Yes".');
                // }
            });

        if ($reqvalidator->fails()) {
            // Emit event for scrolling to the first error
            $this->setErrorBag($reqvalidator->errors()); // Set the error bag for Livewire
            $this->dispatch('scrollToError');
            return false;
        } else {
            return true;
        }
    }

    public function addLog()
    {
        $this->logs[] = $this->logdetails;
        $this->logdetails = null;

        $this->dispatch('close-log-modal');
        $this->dispatch('show-message', message: 'Log added successfully');
    }

    public function removeLog($index)
    {
        unset($this->logs[$index]);
    }

    public function updating($name, $value)
    {
        if ($name == 'requesting_unit' || $name == 'uploads' || $name == 'source_of_funds') {
            $this->skipRender();
        } else {
            $this->dispatch('preserveScroll');
        }
    }
}
