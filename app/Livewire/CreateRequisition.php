<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Models\CurrentFinancialYear;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\RequisitionRequestForm;
use App\Models\User;
use App\Models\Vote;
use App\RequestFormStatus;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateRequisition extends Component
{
    use WithFileUploads;

    #[Title('Create Requsition')]

    public $form;
    public $requisition_status;
    public $requisition_no;
    public $requesting_unit;
    public $file_no;
    public $item;
    public $source_of_funds;
    public $assigned_to;
    public $date_assigned;
    public $date_received_procurement;
    public $site_visit = false;
    public $site_visit_date;
    public $tender_issue_date;
    public $tender_deadline_date;
    public $evaluation_start_date;
    public $evaluation_end_date;
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
    public $vendors = [];

    public function mount(RequisitionRequestForm $form)
    {
        $this->form = $form;
        $this->requisition_no = CurrentFinancialYear::generateRequisitionNo();
        $this->departments = Department::all();
        $this->staff = User::procurement()->get();
        $this->votes = Vote::active()->get();
        $this->requesting_unit = $form->requesting_unit;
        $this->item = $form->items->pluck('name')->implode(', ');
        $this->date_received_procurement = $form->reporting_officer_approval_date->format('Y-m-d');
        $this->source_of_funds = $form->votes->first()->number;
    }


    public function render()
    {
        return view('livewire.create-requisition');
    }

    public function save()
    {
        try {

            if ($this->site_visit_date === '') {
                $this->site_visit_date = null;
            }

            if ($this->tender_issue_date === '') {
                $this->tender_issue_date = null;
            }

            if ($this->tender_deadline_date === '') {
                $this->tender_deadline_date = null;
            }

            if ($this->evaluation_start_date === '') {
                $this->evaluation_start_date = null;
            }

            if ($this->evaluation_end_date === '') {
                $this->evaluation_end_date = null;
            }

            if ($this->date_assigned === '') {
                $this->date_assigned = null;
            }

            if ($this->date_sent_dps === '') {
                $this->date_sent_dps = null;
            }

            if ($this->ps_approval_date === '') {
                $this->ps_approval_date = null;
            }

            if ($this->date_received_procurement === '') {
                $this->date_received_procurement = null;
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

            if ($this->assigned_to === '') {
                $this->assigned_to = null;
            }

            $newrequisition = Requisition::create([
                'requisition_status' => $this->requisition_status,
                'requisition_no' => $this->requisition_no,
                'requesting_unit' => $this->requesting_unit,
                'file_no' => $this->file_no,
                'item' => $this->item,
                'source_of_funds' => $this->source_of_funds,
                'assigned_to' => $this->assigned_to,
                'date_assigned' => $this->date_assigned,
                'date_received_procurement' => $this->date_received_procurement,
                'site_visit' => $this->site_visit,
                'site_visit_date' => $this->site_visit_date,
                'tender_issue_date' => $this->tender_issue_date,
                'tender_deadline_date' => $this->tender_deadline_date,
                'evaluation_start_date' => $this->evaluation_start_date,
                'evaluation_end_date' => $this->evaluation_end_date,
                'date_sent_dps' => $this->date_sent_dps,
                'ps_approval' => $this->ps_approval,
                'ps_approval_date' => $this->ps_approval_date,
                'vendor_name' => $this->vendor_name,
                'amount' => $this->amount,
                'denied_note' => $this->denied_note,
                'created_by' => Auth::user()->username,
            ]);

            if (count($this->vendors) > 0) {
                foreach ($this->vendors as $vendor) {
                    $newrequisition->vendors()->create([
                        'vendor_name' => $vendor['vendor_name'],
                        'amount' => $vendor['amount'],
                    ]);
                }
            }

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
            $this->form->update([
                'requisition_id' => $newrequisition->id,
                'status' => RequestFormStatus::COMPLETED,
            ]);

            Log::info('Requisition #' . $this->requisition_no . ' was created by ' . Auth::user()->name);
            return redirect()->route('requisitions.index')->with('success', 'Requisition created successfully');
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while creating a requisition: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error creating requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function validateForm()
    {

        $reqvalidator = Validator::make(
            [
                'requisition_no' => $this->requisition_no,
                'requesting_unit' => $this->requesting_unit,
                'file_no' => $this->file_no,
                'item' => $this->item,
                'assigned_to' => $this->assigned_to,
                'date_assigned' => $this->date_assigned,
                'date_sent_dps' => $this->date_sent_dps,
                'ps_approval' => $this->ps_approval,
                'date_received_procurement' => $this->date_received_procurement,
                'site_visit' => $this->site_visit,
                'site_visit_date' => $this->site_visit_date,
                'tender_issue_date' => $this->tender_issue_date,
                'tender_deadline_date' => $this->tender_deadline_date,
                'evaluation_start_date' => $this->evaluation_start_date,
                'evaluation_end_date' => $this->evaluation_end_date,
                // 'sent_to_cb' => $this->sent_to_cb,
                // 'date_sent_cb' => $this->date_sent_cb,
                'vendors' => $this->vendors,
            ],
            [
                'requisition_no' => 'required|unique:requisitions',
                'requesting_unit' => 'required',
                'file_no' => 'nullable',
                'item' => 'required',
                'site_visit' => 'boolean',
                //Site visit date is required if site visit is true
                'site_visit_date' => 'nullable|date|date_format:Y-m-d|required_if:site_visit,true',
                'tender_issue_date' => 'nullable|date|date_format:Y-m-d',
                'tender_deadline_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:tender_issue_date',
                'evaluation_start_date' => 'nullable|date|date_format:Y-m-d',
                'evaluation_end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:evaluation_start_date',
                'assigned_to' => 'nullable',
                'date_assigned' => 'nullable|date|before_or_equal:today|date_format:Y-m-d',
                'date_received_procurement' => 'required|date|before_or_equal:today|date_format:Y-m-d',
                'date_sent_dps' => 'nullable|date|before_or_equal:today|date_format:Y-m-d',
                'vendors.*.vendor_name' => 'required',
                'vendors.*.amount' => 'required|numeric',
                // 'date_sent_cb' => 'nullable|sometimes|after:date_sent_dps',
            ],
            [
                'vendors.*.vendor_name.required' => 'Vendor name is required',
                'vendors.*.amount.required' => 'Amount is required',
                'vendors.*.amount.numeric' => 'Amount must be a number',
            ],
        )
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

    public function addVendor()
    {
        $this->vendors[] = ['name' => '', 'amount' => ''];
    }

    public function removeVendor($index)
    {
        unset($this->vendors[$index]);
    }
}
