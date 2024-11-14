<?php

namespace App\Livewire;

use App\Mail\NotifyCostBudgeting;
use App\Mail\NotifyVoteControl;
use App\Models\CBRequisition;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\Status;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ViewRequisition extends Component
{
    use WithFileUploads;

    public $isEditingProcurement1 = false;
    public $isEditingProcurement2 = false;

    public $today;

    public $requisition;
    public $requisition_status;
    public $requisition_no;
    public $requesting_unit;
    public $file_no;
    public $item;
    public $source_of_funds;
    public $assigned_to;
    public $date_assigned;
    public $date_sent_dps;
    public $ps_approval;
    public $vendor_name;
    public $amount;
    public $denied_note;
    public $ps_approval_date;
    public $sent_to_cb;
    public $date_sent_cb;
    public $active_pane = 'procurement1';
    public $uploads;
    public $upload;

    //Cost & Budgeting

    public $date_sent_request_mof;
    public $request_no;
    public $release_no;
    public $release_date;
    public $change_of_vote_no;

    //Procurement 2
    public $purchase_order_no;
    public $eta;
    public $date_sent_commit;
    public $invoice_no;
    public $date_invoice_received;
    public $date_sent_ap;

    //Accounts
    public $batch_no;
    public $voucher_no;
    public $date_received_from_vc;
    public $date_of_cheque;
    public $cheque_no;
    public $date_sent_chequeprocessing;

    public $date_completed;

    public $logdetails;

    public $departments;
    public $staff;
    public $logs;


    public function render()
    {
        $this->logs = $this->requisition->statuslogs;
        $this->uploads = $this->requisition->file_uploads()->get();
        return view('livewire.view-requisition')->title($this->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->today = Carbon::now()->format('Y-m-d');
        $this->departments = Department::all();
        $this->staff = User::procurement()->get();

        $this->requisition = Requisition::find($id);
        $this->requisition_status = $this->requisition->requisition_status;
        $this->requisition_no = $this->requisition->requisition_no;
        $this->requesting_unit = $this->requisition->requesting_unit;
        $this->file_no = $this->requisition->file_no;
        $this->item = $this->requisition->item;
        $this->source_of_funds = $this->requisition->source_of_funds;
        $this->assigned_to = $this->requisition->assigned_to;
        $this->date_assigned = $this->requisition->date_assigned;
        $this->date_sent_dps = $this->requisition->date_sent_dps;
        $this->ps_approval = $this->requisition->ps_approval;
        $this->vendor_name = $this->requisition->vendor_name;
        $this->amount = $this->requisition->amount;
        $this->denied_note = $this->requisition->denied_note;
        $this->ps_approval_date = $this->requisition->ps_approval_date;
        $this->sent_to_cb = $this->requisition->sent_to_cb;
        $this->date_sent_cb = $this->requisition->date_sent_cb;
        $this->date_completed = $this->requisition->date_completed;

        //Cost & Budgeting
        $this->date_sent_request_mof = $this->requisition->date_sent_request_mof;
        $this->request_no = $this->requisition->request_no;
        $this->release_no = $this->requisition->release_no;
        $this->release_date = $this->requisition->release_date;
        $this->change_of_vote_no = $this->requisition->change_of_vote_no;

        //Procurement 2
        $this->purchase_order_no = $this->requisition->purchase_order_no;
        $this->eta = $this->requisition->eta;
        $this->date_sent_commit = $this->requisition->date_sent_commit;
        $this->invoice_no = $this->requisition->invoice_no;
        $this->date_invoice_received = $this->requisition->date_invoice_received;
        $this->date_sent_ap = $this->requisition->date_sent_ap;

        //Accounts
        $this->batch_no = $this->requisition->batch_no;
        $this->voucher_no = $this->requisition->voucher_no;
        $this->date_received_from_vc = $this->requisition->date_received_from_vc;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_sent_chequeprocessing = $this->requisition->date_sent_chequeprocessing;

        if ($this->requisition->cost_budgeting_requisition && !$this->requisition->cost_budgeting_requisition->is_completed) {
            $this->active_pane = 'cost_and_budgeting';
        }

        if ($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed) {
            $this->active_pane = 'procurement2';
        }

        if ($this->requisition->vote_control_requisition) {
            $this->active_pane = 'votecontrol';
        }
    }

    public function edit()
    {
        if (!$this->validateForm()) {
            return;  // Stop execution if form validation fails
        }

        Requisition::where('id', $this->requisition->id)->update([
            'requisition_no' => $this->requisition_no,
            'requesting_unit' => $this->requesting_unit,
            'file_no' => $this->file_no,
            'item' => $this->item,
            'source_of_funds' => $this->source_of_funds,
            'assigned_to' => $this->assigned_to,
            'date_sent_dps' => $this->date_sent_dps,
            'ps_approval' => $this->ps_approval,
            'ps_approval_date' => $this->ps_approval_date,
            'vendor_name' => $this->vendor_name,
            'amount' => $this->amount,
            'denied_note' => $this->denied_note,
            'updated_by' => Auth::user()->username,
        ]);

        if (!$this->requisition->cost_budgeting_requisition) {
            $this->setRequisitionStatus();
        }

        $this->isEditingProcurement1 = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Requisition edited successfully');
        $this->requisition = $this->requisition->fresh();
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
            'requisition_no' => 'required',
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

    public function setRequisitionStatus()
    {
        $status = $this->requisition_status;

        if ($this->ps_approval === 'Not Sent') {
            $status = 'To be Sent to DPS';
        }

        if ($this->ps_approval === 'Pending') {
            $status = 'Pending PS Approval';
        }

        if ($this->ps_approval === 'Approval Denied') {
            $status = 'Denied by PS';
        }

        if ($this->ps_approval === 'Approved') {
            $status = 'Approved by PS';
        }

        if ($this->requisition->cost_budgeting_requisition) {
            $status = 'To Be Sent to MoF';
        }

        if ($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed) {
            $status = 'Sent to Procurement';
        }

        if ($this->requisition->vote_control_requisition) {
            $status = 'Sent to Vote Control';
        }

        if ($this->requisition->vote_control_requisition && $this->requisition->vote_control_requisition->is_completed) {
            $status = 'Completed';
        }

        $this->requisition->update([
            'requisition_status' => $status,
        ]);
    }


    public function uploadFile()
    {

        $this->validate([
            'upload' => 'required|file|max:1024',
        ], [
            'upload.required' => 'Please upload a file before proceeding.',
            'upload.max' => 'The file must not be larger than 1MB.',
        ]);
        $filename = $this->upload->getClientOriginalName();

        $path = $this->upload->store('file_uploads', 'public');
        $this->requisition->file_uploads()->create([
            'file_name' => $filename,
            'file_path' => $path,
            'uploaded_by' => Auth::user()->username,
        ]);

        $this->upload = null;
        $this->requisition = $this->requisition->fresh();
        $this->dispatch('show-message', message: 'File uploaded successfully');
    }


    public function deleteFile($id)
    {
        $upload = $this->requisition->file_uploads->where('id', $id)->first();
        $upload->delete();
        Storage::delete('public/' . $upload->file_path);
        $this->dispatch('show-message', message: 'File deleted successfully');
        $this->dispatch('preserveScroll');
    }

    public function addLog()
    {

        $this->requisition->statuslogs()->create([
            'details' => $this->logdetails,
            'created_by' => Auth::user()->username,
        ]);

        $this->dispatch('close-log-modal');
        $this->dispatch('preserveScroll');
        $this->dispatch('show-message', message: 'Log added successfully');
    }

    public function deleteLog($id)
    {
        $log = Status::find($id);
        $log->delete();
        $this->dispatch('show-message', message: 'Log deleted successfully');
        $this->dispatch('preserveScroll');
    }
    public function getIsSendCBButtonDisabledProperty()
    {
        return $this->requisition_no === null || trim($this->requisition_no) === '' ||
            $this->requesting_unit === null || trim($this->requesting_unit) === '' ||
            $this->file_no === null || trim($this->file_no) === '' ||
            $this->item === null || trim($this->item) === '' ||
            $this->assigned_to === null || trim($this->assigned_to) === '' ||
            $this->date_sent_dps === null || trim($this->date_sent_dps) === '' ||
            $this->ps_approval === null || $this->ps_approval !== 'Approved';
    }

    public function getFormattedDateAssigned()
    {
        if ($this->date_assigned) {
            return Carbon::parse($this->date_assigned)->format('F jS, Y');
        }
    }


    public function getFormattedDateSentCB()
    {
        if ($this->date_sent_cb) {
            return Carbon::parse($this->date_sent_cb)->format('F jS, Y');
        }
    }

    public function getFormattedDateSentPs()
    {
        if ($this->date_sent_dps) {
            return Carbon::parse($this->date_sent_dps)->format('F jS, Y');
        }
    }

    public function sendToCB()
    {

        $this->requisition->update([
            'requisition_status' => 'To Be Sent to MoF',
            'sent_to_cb' => true,
            'date_sent_cb' => Carbon::now(),
            'updated_by' => Auth::user()->username,
        ]);

        $this->requisition->cost_budgeting_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        //Send email to Cost & Budgeting
        // Mail::to('jardel.regis@health.gov.tt')->send(new NotifyCostBudgeting($this->requisition));

        return redirect()->route('requisitions.view', ['id' => $this->requisition->id])->with('success', 'Requisition sent to Cost & Budgeting');
    }

    //Cost & Budgeting

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

    public function getDateCompletedCB()
    {
        if ($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed) {
            return Carbon::parse($this->requisition->cost_budgeting_requisition->date_completed)->format('F jS, Y');
        }
    }

    //Procurement 2

    public function getIsButtonProcurement2DisabledProperty()
    {
        return $this->purchase_order_no === null || trim($this->purchase_order_no) === '' ||
            $this->eta === null || $this->eta === '' ||
            $this->date_sent_commit === null || $this->date_sent_commit === '' ||
            $this->invoice_no === null || trim($this->invoice_no) === '' ||
            $this->date_invoice_received === null || $this->date_invoice_received === '' ||
            $this->date_sent_ap === null || $this->date_sent_ap === '';
    }

    public function getFormattedEta()
    {
        if ($this->eta) {
            return Carbon::parse($this->eta)->format('F jS, Y');
        }
    }

    public function getFormattedDateSentCommit()
    {
        if ($this->date_sent_commit) {
            return Carbon::parse($this->date_sent_commit)->format('F jS, Y');
        }
    }

    public function getFormattedDateInvoiceReceived()
    {
        if ($this->date_invoice_received) {
            return Carbon::parse($this->date_invoice_received)->format('F jS, Y');
        }
    }

    public function getFormattedDateSentAP()
    {
        if ($this->date_sent_ap) {
            return Carbon::parse($this->date_sent_ap)->format('F jS, Y');
        }
    }

    public function editProcurement2()
    {
        if ($this->eta === '') {
            $this->eta = null;
        }

        if ($this->date_sent_commit === '') {
            $this->date_sent_commit = null;
        }

        if ($this->date_invoice_received === '') {
            $this->date_invoice_received = null;
        }

        if ($this->date_sent_ap === '') {
            $this->date_sent_ap = null;
        }

        $status = $this->requisition_status;

        $this->validate([
            'eta' => 'nullable|date|after_or_equal:today',
            'date_sent_commit' => 'nullable|date|after_or_equal:' . $this->requisition->cost_budgeting_requisition->date_completed,
            // 'date_invoice_received' => 'required|date|after_or_equal:today',
            // 'date_sent_ap' => 'nullable|date|after_or_equal:date_sent_commit',
        ]);

        if (!$this->requisition->vote_control_requisition) {
            if ($this->purchase_order_no && $this->eta && $this->date_sent_commit && !$this->invoice_no && !$this->date_invoice_received && !$this->date_sent_ap) {
                $status = 'Awaiting Invoice';
            } elseif ($this->purchase_order_no && $this->eta && $this->date_sent_commit && $this->invoice_no && $this->date_invoice_received && !$this->date_sent_ap) {
                $status = 'To Be Sent to AP';
            } elseif ($this->purchase_order_no && $this->eta && $this->date_sent_commit && $this->invoice_no && $this->date_invoice_received && $this->date_sent_ap && !$this->requisition->vote_control_requisition) {
                $status = 'To Be Sent to AP';
            }
        }


        $this->requisition->update([
            'requisition_status' => $status,
            'purchase_order_no' => $this->purchase_order_no,
            'eta' => $this->eta,
            'date_sent_commit' => $this->date_sent_commit,
            'invoice_no' => $this->invoice_no,
            'date_invoice_received' => $this->date_invoice_received,
            'date_sent_ap' => $this->date_sent_ap,
            'updated_by' => Auth::user()->username,
        ]);

        // $this->setRequisitionStatus();

        $this->isEditingProcurement2 = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Requisition edited successfully');
    }

    public function sendToAccounts()
    {

        $this->requisition->update([
            'requisition_status' => 'Sent to Vote Control',
            'updated_by' => Auth::user()->username,
        ]);

        $this->requisition->vote_control_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        //Send email to Accounts
        // Mail::to('jardel.regis@health.gov.tt')->send(new NotifyVoteControl($this->requisition));

        return redirect()->route('requisitions.view', ['id' => $this->requisition->id])->with('success', 'Requisition sent to Vote Control');
    }

    //Accounts

    public function getFormattedDateSentChequeroom()
    {
        if ($this->date_received_from_vc) {
            return Carbon::parse($this->date_received_from_vc)->format('F jS, Y');
        }
    }

    public function getFormattedDateOfCheque()
    {
        if ($this->date_of_cheque) {
            return Carbon::parse($this->date_of_cheque)->format('F jS, Y');
        }
    }

    public function getFormattedDateChequeForwarded()
    {
        if ($this->date_sent_chequeprocessing) {
            return Carbon::parse($this->date_sent_chequeprocessing)->format('F jS, Y');
        }
    }

    public function updating($name, $value)
    {
        if ($name == 'requesting_unit' || $name == 'upload') {
            $this->skipRender();
        } else {
            $this->dispatch('preserveScroll');
        }
    }
}
