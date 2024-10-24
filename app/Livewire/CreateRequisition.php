<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Requisition;
use App\Models\User;
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
    public $file_number;
    public $item;
    public $source_of_funds;
    public $assigned_to;
    public $date_sent_ps;
    public $ps_approval = 'Not Sent';
    public $ps_approval_date;
    public $sent_to_dfa;
    public $date_sent_dfa;
    public $uploads;

    public $logdetails;

    public $departments;
    public $staff;
    public $logs = [];

    public function mount()
    {
        $this->departments = Department::all();
        $this->staff = User::procurement()->get();
    }


    public function render()
    {
        return view('livewire.create-requisition');
    }

    public function save()
    {

        if (!$this->validateForm()) {
            return;  // Stop execution if form validation fails
        }

        if ($this->ps_approval === 'Not Sent' || $this->ps_approval === 'Pending') {
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
            'requisition_no' => $this->requisition_no,
            'requesting_unit' => $this->requesting_unit,
            'file_number' => $this->file_number,
            'item' => $this->item,
            'source_of_funds' => $this->source_of_funds,
            'assigned_to' => $this->assigned_to,
            'date_sent_ps' => $this->date_sent_ps,
            'ps_approval' => $this->ps_approval,
            'ps_approval_date' => $this->ps_approval_date,
            'created_by' => Auth::user()->name,
        ]);

        foreach ($this->logs as $log) {
            $newrequisition->statuslogs()->create([
                'details' => $log,
                'created_by' => Auth::user()->name,
            ]);
        }


        if (!is_null($this->uploads)) {
            foreach ($this->uploads as $photo) {
                $filename = $photo->getClientOriginalName();
                $path = $photo->store('file_uploads', 'public');
                $newrequisition->file_uploads()->create([
                    'file_name' => $filename,
                    'file_path' => $path,
                    // 'uploaded_by' => auth()->user()->name,
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
            'file_number' => $this->file_number,
            'item' => $this->item,
            'assigned_to' => $this->assigned_to,
            'date_sent_ps' => $this->date_sent_ps,
            'ps_approval' => $this->ps_approval,
            // 'sent_to_dfa' => $this->sent_to_dfa,
            // 'date_sent_dfa' => $this->date_sent_dfa,
        ], [
            'requisition_no' => 'required',
            'requesting_unit' => 'required',
            'file_number' => 'required',
            'item' => 'required',
            'assigned_to' => 'required',
            'date_sent_ps' => 'nullable|date|before_or_equal:today',
            // 'date_sent_dfa' => 'nullable|sometimes|after:date_sent_ps',
        ])
            ->after(function ($validator) {
                // Ensure 'ps_approval' is not 'Not Sent' if 'date_sent_ps' is populated
                if ($this->date_sent_ps !== null && $this->ps_approval === 'Not Sent') {
                    $validator->errors()->add('ps_approval', 'PS approval cannot be "Not Sent" if the date sent to PS is populated.');
                }

                // Ensure 'date_sent_ps' is not null if 'ps_approval' is not 'Not Sent'
                if ($this->ps_approval !== 'Not Sent' && $this->date_sent_ps === null) {
                    $validator->errors()->add('date_sent_ps', 'Date sent to PS cannot be null if PS Approval is set to ' . $this->ps_approval);
                }

                // Ensure 'date_sent_dfa' is not null if 'sent_to_dfa' is "Yes"
                // if ($this->sent_to_dfa === 'Yes' && $this->date_sent_dfa === null) {
                //     $validator->errors()->add('date_sent_dfa', 'Date sent to DFA cannot be null if sent to DFA is "Yes".');
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
}
