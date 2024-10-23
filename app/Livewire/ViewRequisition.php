<?php

namespace App\Livewire;

use App\Models\CBRequisition;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Container\Attributes\Auth;
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
    public $file_number;
    public $item;
    public $source_of_funds;
    public $assigned_to;
    public $date_sent_ps;
    public $ps_approval;
    public $ps_approval_date;
    public $sent_to_dfa;
    public $date_sent_dfa;
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
    public $date_sent_chequeroom;
    public $date_of_cheque;
    public $cheque_no;
    public $date_cheque_forwarded;

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
        $this->file_number = $this->requisition->file_number;
        $this->item = $this->requisition->item;
        $this->source_of_funds = $this->requisition->source_of_funds;
        $this->assigned_to = $this->requisition->assigned_to;
        $this->date_sent_ps = $this->requisition->date_sent_ps;
        $this->ps_approval = $this->requisition->ps_approval;
        $this->ps_approval_date = $this->requisition->ps_approval_date;
        $this->sent_to_dfa = $this->requisition->sent_to_dfa;
        $this->date_sent_dfa = $this->requisition->date_sent_dfa;

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
        $this->date_sent_chequeroom = $this->requisition->date_sent_chequeroom;
        $this->date_of_cheque = $this->requisition->date_of_cheque;
        $this->cheque_no = $this->requisition->cheque_no;
        $this->date_cheque_forwarded = $this->requisition->date_cheque_forwarded;

        if ($this->requisition->cost_budgeting_requisition && !$this->requisition->cost_budgeting_requisition->is_completed) {
            $this->active_pane = 'cost_and_budgeting';
        }

        if($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed){
            $this->active_pane = 'procurement2';
        }

        if($this->requisition->accounts_requisition){
            $this->active_pane = 'accounts';
        }
    }

    public function edit()
    {
        if (!$this->validateForm()) {
            return;  // Stop execution if form validation fails
        }
        
        Requisition::where('id', $this->requisition->id)->update([
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
            // 'sent_to_dfa' => $this->sent_to_dfa,
            // 'date_sent_dfa' => $this->date_sent_dfa,
        ]);

        $this->setRequisitionStatus();

        $this->isEditingProcurement1 = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Requisition edited successfully');
        $this->requisition = $this->requisition->fresh();
    }

    public function validateForm(){
        
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
        }else{
            return true;
        }
    }

    public function setRequisitionStatus(){
        $status = $this->requisition_status;
        
        if ($this->ps_approval === 'Not Sent' || $this->ps_approval === 'Pending') {
            $status = 'Pending PS Approval';
        }
    
        if ($this->ps_approval === 'Approval Denied') {
            $status = 'Denied by PS';
        }
    
        if ($this->ps_approval === 'Approved') {
            $status = 'Approved by PS';
        }
    
        if ($this->requisition->cost_budgeting_requisition) {
            $status = 'Sent to Cost & Budgeting';
        }
    
        if ($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed) {
            $status = 'Sent to Procurement';
        }
    
        if ($this->requisition->accounts_requisition) {
            $status = 'Sent to Cheque Dispatch';
        }
    
        if ($this->requisition->accounts_requisition && $this->requisition->accounts_requisition->is_completed) {
            $status = 'Completed';
        }

        $this->requisition->update([
            'requisition_status' => $status,
        ]);
    }
    

    public function uploadFile(){

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
            // 'uploaded_by' => auth()->user()->name,
        ]);

        $this->upload = null;
        $this->requisition = $this->requisition->fresh();
        $this->dispatch('show-message', message: 'File uploaded successfully');
    }
    

    public function deleteFile($id){
        $upload = $this->requisition->file_uploads->where('id', $id)->first();
        $upload->delete();
        Storage::delete('public/' . $upload->file_path);
        $this->dispatch('show-message', message: 'File deleted successfully');
    }

    public function addLog(){
        
        $this->requisition->statuslogs()->create([
            'details' => $this->logdetails,
            // 'user_id' => auth()->id(),
        ]);

        $this->dispatch('close-log-modal');
        $this->dispatch('show-message', message: 'Log added successfully');
    }
    public function getIsSendCBButtonDisabledProperty()
    {
        return $this->requisition_no === null || trim($this->requisition_no) === '' ||
               $this->requesting_unit === null || trim($this->requesting_unit) === '' ||
               $this->file_number === null || trim($this->file_number) === '' ||
               $this->item === null || trim($this->item) === '' ||
               $this->assigned_to === null || trim($this->assigned_to) === '' ||
               $this->date_sent_ps === null || trim($this->date_sent_ps) === '' ||
               $this->ps_approval === null || $this->ps_approval !== 'Approved';
    }
    

    public function getFormattedDateSentDfa()
    {
        if ($this->date_sent_dfa) {
            return Carbon::parse($this->date_sent_dfa)->format('F jS, Y');
        }
    }

    public function getFormattedDateSentPs()
    {
        if ($this->date_sent_ps) {
            return Carbon::parse($this->date_sent_ps)->format('F jS, Y');
        }
    }

    public function sendToCB(){

        $this->requisition->update([
            'requisition_status' => $this->requisition_status,
            'sent_to_dfa' => true,
            'date_sent_dfa' => Carbon::now(),
        ]);

        $this->requisition->cost_budgeting_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        $this->setRequisitionStatus();
        //Send email to DFA

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

    public function editProcurement2(){

        $this->requisition->update([
            'requisition_status' => $this->requisition_status,
            'purchase_order_no' => $this->purchase_order_no,
            'eta' => $this->eta,
            'date_sent_commit' => $this->date_sent_commit,
            'invoice_no' => $this->invoice_no,
            'date_invoice_received' => $this->date_invoice_received,
            'date_sent_ap' => $this->date_sent_ap,
        ]);

        $this->setRequisitionStatus();

        $this->isEditingProcurement2 = false;
        $this->resetValidation();
        $this->dispatch('show-message', message: 'Requisition edited successfully');
    }

    public function sendToAccounts(){

        $this->requisition->update([
            'requisition_status' => $this->requisition_status,
        ]);
    
        $this->requisition->accounts_requisition()->create([
            'date_received' => Carbon::now(),
        ]);
        
        $this->setRequisitionStatus();

        //Send email to Accounts

        return redirect()->route('requisitions.view', ['id' => $this->requisition->id])->with('success', 'Requisition sent to Cheque Dispatch');
    }

    //Accounts
    

    public function getFormattedDateSentChequeroom(){
        if($this->date_sent_chequeroom){
            return Carbon::parse($this->date_sent_chequeroom)->format('F jS, Y');
        }
    }

    public function getFormattedDateOfCheque(){
        if($this->date_of_cheque){
            return Carbon::parse($this->date_of_cheque)->format('F jS, Y');
        }
    }

    public function getFormattedDateChequeForwarded(){
        if($this->date_cheque_forwarded){
            return Carbon::parse($this->date_cheque_forwarded)->format('F jS, Y');
        }
    }

}
