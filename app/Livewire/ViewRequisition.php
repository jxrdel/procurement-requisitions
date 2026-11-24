<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Models\CBRequisition;
use App\Models\Cheque;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\RequisitionVendor;
use App\Models\Status;
use App\Models\User;
use App\Models\VendorInvoice;
use App\Models\Vote;
use App\Notifications\NotifyAccountsPayable;
use App\Notifications\NotifyCostBudgeting;
use App\Notifications\NotifyVoteControl;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use App\RequestFormStatus;
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
    public $date_received_procurement;
    public $actual_cost;
    public $funding_availability;
    public $date_sent_aov_procurement;
    public $note_to_ps = false;
    public $note_to_ps_date;
    public $site_visit = false;
    public $site_visit_date;
    public $tender_type;
    public $is_first_pass;
    public $tender_issue_date;
    public $tender_deadline_date;
    public $evaluation_start_date;
    public $evaluation_end_date;
    public $date_sent_dps;
    public $ps_approval;
    public $amount;
    public $denied_note;
    public $ps_approval_date;
    public $sent_to_cb;
    public $date_sent_cb;
    public $active_pane = 'procurement1';
    public $uploads;
    public $upload;
    public $vendors = [];
    public $originalVendorIds = [];

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

    //Vendor Modal

    public $vendor;
    public $vendor_name;
    public $invoice_amount;
    public $invoices = [];
    public $invoice_count = 0;

    //Cheques
    public $cheques;

    //Button Invoice Count
    public $invoice_count_buttons = [];

    public $date_completed;

    public $logdetails;

    public $cancelation_reason;

    public $departments;
    public $staff;
    public $logs;
    public $votes;

    public $backUrl;


    public function render()
    {
        $this->invoice_count_buttons = [];

        // Populate invoiceCounts dynamically
        foreach ($this->requisition->vendors as $vendor) {
            $this->invoice_count_buttons[$vendor->id] = VendorInvoice::where('vendor_id', $vendor->id)->count();
        }
        $this->logs = $this->requisition->statuslogs;
        $this->uploads = $this->requisition->file_uploads()->get();
        return view('livewire.view-requisition')->title($this->requisition_no . ' | View Requisition');
    }

    public function mount($id)
    {
        $this->today = Carbon::now()->format('Y-m-d');
        $this->departments = Department::all();
        $this->votes = Vote::active()->get();
        $this->staff = User::procurement()->get();

        $this->requisition = Requisition::find($id);

        if (Gate::denies('view-requisition', $this->requisition)) {
            abort(403, 'You do not have permission to view this requisition.');
        }

        if (!$this->requisition) {
            return abort(404);
        }

        if (Route::currentRouteName() == 'queue.requisition.view') {
            $this->backUrl = route('queue');
        } else {
            $this->backUrl = route('requisitions.index');
        }
        $this->requisition_status = $this->requisition->requisition_status;
        $this->requisition_no = $this->requisition->requisition_no;
        $this->requesting_unit = $this->requisition->requesting_unit;
        $this->file_no = $this->requisition->file_no;
        $this->item = $this->requisition->item;
        $this->source_of_funds = $this->requisition->source_of_funds;
        $this->assigned_to = $this->requisition->assigned_to;
        $this->date_assigned = $this->requisition->date_assigned;
        $this->date_received_procurement = $this->requisition->date_received_procurement;
        $this->actual_cost = $this->requisition->actual_cost;
        $this->funding_availability = $this->requisition->funding_availability;
        $this->date_sent_aov_procurement = $this->requisition->date_sent_aov_procurement;
        $this->note_to_ps = $this->requisition->note_to_ps;
        $this->note_to_ps_date = $this->requisition->note_to_ps_date;
        $this->site_visit = $this->requisition->site_visit;
        $this->site_visit_date = $this->requisition->site_visit_date;
        $this->tender_type = $this->requisition->tender_type;
        $this->is_first_pass = $this->requisition->is_first_pass;
        $this->tender_issue_date = $this->requisition->tender_issue_date;
        $this->tender_deadline_date = $this->requisition->tender_deadline_date;
        $this->evaluation_start_date = $this->requisition->evaluation_start_date;
        $this->evaluation_end_date = $this->requisition->evaluation_end_date;
        $this->date_sent_dps = $this->requisition->date_sent_dps;
        $this->ps_approval = $this->requisition->ps_approval;
        $this->amount = $this->requisition->amount;
        $this->denied_note = $this->requisition->denied_note;
        $this->ps_approval_date = $this->requisition->ps_approval_date;
        $this->sent_to_cb = $this->requisition->sent_to_cb;
        $this->date_sent_cb = $this->requisition->date_sent_cb;
        $this->date_completed = $this->requisition->date_completed;
        $this->loadVendors();
        $this->originalVendorIds = $this->savedVendorIds();

        //Cost & Budgeting
        $this->date_sent_request_mof = $this->requisition->date_sent_request_mof;
        $this->request_no = $this->requisition->request_no;
        $this->release_no = $this->requisition->release_no;
        $this->release_date = $this->requisition->release_date;

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

        //Cheque Processing
        $this->cheques = $this->requisition->vendors()->with('cheques')->get()->pluck('cheques')->flatten();

        $this->setActivePane();
    }

    public function setActivePane()
    {
        $vendors = $this->requisition->vendors; // Assuming a `hasMany` relationship exists

        if (!$this->requisition->cost_budgeting_requisition) {
            $this->active_pane = 'procurement1';
            return;
        }

        if ($vendors->isEmpty()) {
            // If no vendors exist, fallback to the existing requisition-based logic
            if ($this->requisition->cost_budgeting_requisition && !$this->requisition->cost_budgeting_requisition->is_completed) {
                $this->active_pane = 'cost_and_budgeting';
            } elseif ($this->requisition->cost_budgeting_requisition && $this->requisition->cost_budgeting_requisition->is_completed) {
                $this->active_pane = 'procurement2';
            }
            return;
        }

        // Define stage priorities in order
        $stages = [
            'cost_and_budgeting' => fn($vendor) => !$vendor->release_no,
            'procurement2' => fn($vendor) => !$vendor->ap,
            'accounts_payable' => fn($vendor) => !$vendor->ap || !$vendor->ap->is_completed,
            'votecontrol' => fn($vendor) => !$vendor->voteControl || !$vendor->voteControl->is_completed,
            'checkroom' => fn($vendor) => !$vendor->checkStaff || !$vendor->checkStaff->is_completed,
            'chequeprocessing' => fn($vendor) => !$vendor->chequeProcessing || !$vendor->chequeProcessing->is_completed,
        ];

        // Iterate through vendors and find the least progressed stage
        foreach ($stages as $stage => $condition) {
            foreach ($vendors as $vendor) {

                if ($this->requisition->requisition_status == 'Sent to Procurement') {
                    $this->active_pane = 'procurement2';
                    return;
                }
                if ($this->requisition->requisition_status == 'Sent to Vote Control for Commitment') {
                    $this->active_pane = 'votecontrol';
                    return;
                }
                if ($condition($vendor)) {
                    $this->active_pane = $stage;
                    return; // Stop as soon as the least progressed stage is found
                }
            }
        }

        // If all vendors are completed, keep the last stage
        $this->active_pane = 'chequeprocessing';
    }


    public function edit()
    {
        $this->nullifyEmptyDates();

        if (!$this->validateForm()) {
            return;
        }

        try {
            Requisition::where('id', $this->requisition->id)->update([
                'requisition_no' => $this->requisition_no,
                'requesting_unit' => $this->requesting_unit,
                'file_no' => $this->file_no,
                'item' => $this->item,
                'actual_cost' => $this->actual_cost,
                'funding_availability' => $this->funding_availability,
                'date_sent_aov_procurement' => $this->date_sent_aov_procurement,
                'site_visit' => $this->site_visit,
                'site_visit_date' => $this->site_visit_date,
                'tender_type' => $this->tender_type,
                'is_first_pass' => $this->is_first_pass,
                'note_to_ps' => $this->note_to_ps,
                'note_to_ps_date' => $this->note_to_ps_date,
                'tender_issue_date' => $this->tender_issue_date,
                'tender_deadline_date' => $this->tender_deadline_date,
                'evaluation_start_date' => $this->evaluation_start_date,
                'evaluation_end_date' => $this->evaluation_end_date,
                'source_of_funds' => $this->source_of_funds,
                'date_received_procurement' => $this->date_received_procurement,
                'assigned_to' => $this->assigned_to,
                'date_sent_dps' => $this->date_sent_dps,
                'ps_approval' => $this->ps_approval,
                'ps_approval_date' => $this->ps_approval_date,
                'amount' => $this->amount,
                'denied_note' => $this->denied_note,
                'updated_by' => Auth::user()->username,
            ]);

            if ($this->assigned_to && !$this->requisition->sent_to_assigned_officer) {
                $this->requisition->update(['sent_to_assigned_officer' => true]);
                $officer = User::find($this->assigned_to);
                if ($officer) {
                    Notification::send($officer, new \App\Notifications\AssignedToRequisition($this->requisition));
                }
            }

            if (!$this->requisition->cost_budgeting_requisition) {
                $this->setRequisitionStatus();
            }

            $currentVendorIds = $this->savedVendorIds();
            $vendorIdsToDelete = array_diff($this->originalVendorIds, $currentVendorIds);

            if (!empty($vendorIdsToDelete)) {
                $this->requisition->vendors()->whereIn('id', $vendorIdsToDelete)->delete();
            }

            foreach ($this->vendors as $index => $vendor) {
                $vendorData = [
                    'vendor_name' => $vendor['vendor_name'],
                    'vendor_items' => $vendor['vendor_items'] ?? null,
                    'amount' => $vendor['amount'],
                ];

                if (!empty($vendor['id'])) {
                    $this->requisition->vendors()->where('id', $vendor['id'])->update($vendorData);
                } else {
                    $newVendor = $this->requisition->vendors()->create($vendorData);
                    $this->vendors[$index]['id'] = $newVendor->id;
                }
            }

            $this->requisition->statuslogs()->create([
                'details' => 'Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name,
                'created_by' => Auth::user()->username,
            ]);

            Log::info('Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->username, [
                'requisition_id' => $this->requisition->id,
                'requisition_no' => $this->requisition->requisition_no,
                'url' => route('requisitions.view', $this->requisition->id, absolute: true),
            ]);
            $this->isEditingProcurement1 = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Requisition edited successfully');
            $this->requisition = $this->requisition->fresh();
            $this->loadVendors();
            $this->originalVendorIds = $this->savedVendorIds();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition: ' . $e->getMessage());
            // Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
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
                'date_received_procurement' => $this->date_received_procurement,
                'date_sent_aov_procurement' => $this->date_sent_aov_procurement,
                'site_visit' => $this->site_visit,
                'site_visit_date' => $this->site_visit_date,
                'note_to_ps' => $this->note_to_ps,
                'note_to_ps_date' => $this->note_to_ps_date,
                'tender_issue_date' => $this->tender_issue_date,
                'tender_deadline_date' => $this->tender_deadline_date,
                'evaluation_start_date' => $this->evaluation_start_date,
                'evaluation_end_date' => $this->evaluation_end_date,
                'ps_approval' => $this->ps_approval,
                'ps_approval_date' => $this->ps_approval_date,
                'vendors' => $this->vendors,
            ],
            $this->procurement1Rules(),
            $this->procurement1Messages(),
            $this->procurement1Attributes(),
        )->after(function ($validator) {
            if ($this->date_sent_dps && $this->ps_approval === 'Not Sent') {
                $validator->errors()->add('ps_approval', 'PS approval cannot be "Not Sent" if the Date sent to DPS is populated.');
            }

            if ($this->ps_approval !== 'Not Sent' && empty($this->date_sent_dps) && !$validator->errors()->has('date_sent_dps')) {
                $validator->errors()->add('date_sent_dps', 'Date sent to DPS is required if PS Approval is set to ' . $this->ps_approval);
            }

            if ($this->ps_approval === 'Approved' && empty($this->ps_approval_date) && !$validator->errors()->has('ps_approval_date')) {
                $validator->errors()->add('ps_approval_date', 'PS Approval Date is required if PS Approval is "Approved".');
            }

            if ($this->site_visit && empty($this->site_visit_date) && !$validator->errors()->has('site_visit_date')) {
                $validator->errors()->add('site_visit_date', 'Site visit date is required when a site visit is required.');
            }

            if ($this->note_to_ps && empty($this->note_to_ps_date) && !$validator->errors()->has('note_to_ps_date')) {
                $validator->errors()->add('note_to_ps_date', 'Note to PS date is required when Note to PS is checked.');
            }

            if ($this->isValidYmdDate($this->tender_issue_date) && $this->isValidYmdDate($this->tender_deadline_date)
                && $this->tender_deadline_date < $this->tender_issue_date) {
                $validator->errors()->add('tender_deadline_date', 'Tender deadline must be on or after the tender issue date.');
            }

            if ($this->isValidYmdDate($this->evaluation_start_date) && $this->isValidYmdDate($this->evaluation_end_date)
                && $this->evaluation_end_date < $this->evaluation_start_date) {
                $validator->errors()->add('evaluation_end_date', 'Evaluation end date must be on or after the evaluation start date.');
            }
        });

        if ($reqvalidator->fails()) {
            $this->setErrorBag($reqvalidator->errors());
            $this->dispatch('scrollToError');
            return false;
        }

        return true;
    }

    public function setRequisitionStatus()
    {
        if ($this->assigned_to) {
            $this->requisition_status = 'Assigned to Procurement Officer';
        }

        if ($this->tender_issue_date === null && $this->tender_deadline_date === null) {
            $this->requisition_status = 'Tender To Be Issued';
        }

        if ($this->tender_issue_date !== null && $this->tender_deadline_date !== null && $this->tender_deadline_date >= $this->today) {
            $this->requisition_status = 'Tender In Progress';
        }

        if ($this->tender_deadline_date !== null && $this->tender_deadline_date < $this->today) {
            $this->requisition_status = 'Tender Completed';
        }

        // If evaluation dates are set and evaluation end date is in the future
        if ($this->evaluation_start_date !== null && ($this->evaluation_end_date === null || $this->evaluation_end_date >= $this->today)) {
            $this->requisition_status = 'Evaluation In Progress';
        }

        // if ($this->evaluation_end_date !== null && $this->evaluation_start_date !== null && $this->evaluation_end_date < $this->today) {
        //     $this->requisition_status = 'Evaluation Completed';
        // }

        if ($this->evaluation_end_date !== null && $this->evaluation_end_date < $this->today && $this->ps_approval === 'Not Sent') {
            $this->requisition_status = 'To be Sent to DPS';
        }

        if ($this->ps_approval === 'Pending') {
            $this->requisition_status = 'Physical File Sent for PS Approval';
        }

        if ($this->ps_approval === 'Approval Denied') {
            $this->requisition_status = 'Denied by PS';
        }

        if ($this->ps_approval === 'Approved') {
            $this->requisition_status = 'Approved by PS';
        }

        $this->requisition->update([
            'requisition_status' => $this->requisition_status,
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

        $this->logdetails = null;

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
        return $this->requisition_no === null || trim((string) $this->requisition_no) === '' ||
            $this->requesting_unit === null || trim((string) $this->requesting_unit) === '' ||
            $this->item === null || trim((string) $this->item) === '' ||
            empty($this->date_sent_dps) ||
            $this->ps_approval === null || $this->ps_approval !== 'Approved' || count($this->requisition->vendors) === 0;
    }

    public function getFormattedDateAssigned()
    {
        return $this->getFormattedDate($this->date_assigned);
    }

    public function getFormattedDateSentCB()
    {
        return $this->getFormattedDate($this->date_sent_cb);
    }

    public function getFormattedDateSentPs()
    {
        return $this->getFormattedDate($this->date_sent_dps);
    }

    public function sendToCB()
    {

        $this->requisition->update([
            'requisition_status' => 'Sent to Cost & Budgeting',
            'sent_to_cb' => true,
            'date_sent_cb' => Carbon::now(),
            'updated_by' => Auth::user()->username,
        ]);

        $this->requisition->cost_budgeting_requisition()->create([
            'date_received' => Carbon::now(),
        ]);

        $this->requisition->statuslogs()->create([
            'details' => 'Requisition #' . $this->requisition->requisition_no . ' was sent to Cost & Budgeting by ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);

        Log::info('Requisition #' . $this->requisition->requisition_no . ' was sent to Cost & Budgeting by ' . Auth::user()->name, [
            'requisition_id' => $this->requisition->id,
            'requisition_no' => $this->requisition->requisition_no,
            'url' => route('requisitions.view', $this->requisition->id, absolute: true),
        ]);

        //Get Cost & Budgeting users
        $users = User::costBudgeting()->get();

        foreach ($users as $user) {
            Notification::send($user, new NotifyCostBudgeting($this->requisition));
            // Mail::to($user->email)->queue(new NotifyCostBudgeting($this->requisition));
        }

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

    public function isProcurement2ButtonDisabled($vendor)
    {
        if (!$this->requisition->is_first_pass && count($vendor['invoices'] ?? []) < 1) {
            return true;
        }

        return
            empty(trim($vendor['purchase_order_no'] ?? '')) ||
            empty($vendor['eta'] ?? '') ||
            empty($vendor['date_sent_commit'] ?? '');
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

        foreach ($this->vendors as &$vendor) {
            if (($vendor['eta'] ?? '') === '') {
                $vendor['eta'] = null;
            }

            if (($vendor['date_sent_commit'] ?? '') === '') {
                $vendor['date_sent_commit'] = null;
            }

            if (($vendor['date_invoice_received'] ?? '') === '') {
                $vendor['date_invoice_received'] = null;
            }

            if (($vendor['date_sent_ap'] ?? '') === '') {
                $vendor['date_sent_ap'] = null;
            }
        }

        unset($vendor);

        $this->updateVendorStatuses();

        $dateSentDps = $this->isValidYmdDate($this->requisition->date_sent_dps)
            ? $this->requisition->date_sent_dps
            : null;

        $this->validate(
            [
                'vendors.*.eta' => 'nullable|bail|date_format:Y-m-d|after_or_equal:today|before_or_equal:9999-12-31',
                'vendors.*.date_sent_commit' => 'nullable|bail|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31' . ($dateSentDps ? '|after_or_equal:' . $dateSentDps : ''),
                'vendors.*.date_sent_ap' => 'nullable|bail|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31',
            ],
            [
                'vendors.*.eta.date_format' => 'Please enter a valid ETA date.',
                'vendors.*.eta.after_or_equal' => 'ETA must be a date on or after today.',
                'vendors.*.eta.before_or_equal' => 'Please enter a valid ETA date.',
                'vendors.*.date_sent_commit.date_format' => 'Please enter a valid date sent for commitment.',
                'vendors.*.date_sent_commit.after_or_equal' => 'Date sent for commitment must be on or after the date sent to DPS.',
                'vendors.*.date_sent_commit.before_or_equal' => 'Please enter a valid date sent for commitment.',
                'vendors.*.date_sent_ap.date_format' => 'Please enter a valid date sent to AP.',
                'vendors.*.date_sent_ap.after_or_equal' => 'Please enter a valid date sent to AP.',
                'vendors.*.date_sent_ap.before_or_equal' => 'Please enter a valid date sent to AP.',
            ]
        );

        try {
            foreach ($this->vendors as $vendor) {
                if (empty($vendor['id'])) {
                    continue;
                }

                $this->requisition->vendors()->where('id', $vendor['id'])->update([
                    'purchase_order_no' => $vendor['purchase_order_no'],
                    'eta' => $vendor['eta'],
                    'date_sent_commit' => $vendor['date_sent_commit'],
                    // 'invoice_no' => $vendor['invoice_no'],
                    // 'date_invoice_received' => $vendor['date_invoice_received'],
                    'date_sent_ap' => $vendor['date_sent_ap'],
                ]);

                if (!$vendor['ap']) {

                    $this->requisition->vendors()->where('id', $vendor['id'])->update([
                        'vendor_status' => $vendor['vendor_status'],
                    ]);
                }
            }

            if (!$this->requisition->is_completed) {
                $this->requisition->update([
                    // 'requisition_status' => $status,
                    'updated_by' => Auth::user()->username,
                ]);
            }

            $this->requisition->statuslogs()->create([
                'details' => 'Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->name,
                'created_by' => Auth::user()->username,
            ]);

            Log::info('Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->username, [
                'requisition_id' => $this->requisition->id,
                'requisition_no' => $this->requisition->requisition_no,
                'url' => route('requisitions.view', $this->requisition->id, absolute: true),
            ]);
            $this->isEditingProcurement2 = false;
            $this->refreshVendors();
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Requisition edited successfully');
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition: ' . $e->getMessage());
            // Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
            dd('Error editing requisition. Please contact the Ministry of Health Helpdesk at 217-4664 ext. 11000 or ext 11124', $e->getMessage());
        }
    }

    public function sendToAP($vendorID)
    {
        $vendor = RequisitionVendor::find($vendorID);

        $vendor->update([
            'sent_to_ap' => true,
            'vendor_status' => 'Sent to Accounts Payable',
        ]);

        if (!$vendor->ap) {
            $vendor->ap()->create([
                'date_received' => Carbon::now(),
            ]);
        } else {
            $vendor->ap()->update([
                'date_received' => Carbon::now(),
                'is_completed' => false,
            ]);
        }

        $this->requisition->update([
            'requisition_status' => 'Sent to Accounts Payable',
            'updated_by' => Auth::user()->username,
        ]);

        if ($this->requisition->is_first_pass) {
            $this->requisition->update([
                'sent_to_ap_first_pass' => true,
            ]);
        }

        if ($this->requisition->is_first_pass) {
            $this->requisition->statuslogs()->create([
                'details' => 'Requisition #' . $this->requisition->requisition_no . ' was sent to Accounts Payable by ' . Auth::user()->name . ' for commitment',
                'created_by' => Auth::user()->username,
            ]);
        } else {
            $this->requisition->statuslogs()->create([
                'details' => 'Vendor ' . $vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Accounts Payable by ' . Auth::user()->name,
                'created_by' => Auth::user()->username,
            ]);
        }

        $vendor->load('ap');
        //Send email to Accounts Payable
        Log::info('Vendor ' . $vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Accounts Payable by ' . Auth::user()->username, [
            'requisition_id' => $this->requisition->id,
            'requisition_no' => $this->requisition->requisition_no,
            'vendor_id' => $vendor->id,
            'url' => route('requisitions.view', $this->requisition->id, absolute: true),
        ]);

        //Get Accounts Payable users
        $users = User::accountsPayable()->get();

        foreach ($users as $user) {
            Notification::send($user, new NotifyAccountsPayable($vendor));
        }

        $this->refreshVendors();

        $this->dispatch('show-message', message: 'Sent to AP successfully');
    }

    public function sendToVoteControl($vendorID)
    {
        $vendor = RequisitionVendor::find($vendorID);
        $vendor->update([
            'vendor_status' => 'Sent to Vote Control for Commitment',
        ]);

        $this->requisition->update([
            'sent_to_vc_first_pass' => true,
            'requisition_status' => 'Sent to Vote Control for Commitment',
        ]);

        if ($vendor->voteControl == null) {
            $vendor->voteControl()->create([
                'date_received' => Carbon::now(),
            ]);
        } else {
            $vendor->voteControl->update([
                'date_received' => Carbon::now(),
                'is_completed' => false,
            ]);
        }
        $vendor->load('voteControl');

        //Send email to Vote Control
        Log::info('Requisition #' . $this->requisition->requisition_no . ' was sent to Vote Control for Commitment by ' . Auth::user()->name . ' from Procurement', [
            'requisition_id' => $this->requisition->id,
            'requisition_no' => $this->requisition->requisition_no,
            'vendor_id' => $vendor->id,
            'url' => route('requisitions.view', $this->requisition->id, absolute: true),
        ]);
        $this->requisition->statuslogs()->create([
            'details' => 'Requisition #' . $this->requisition->requisition_no . ' was sent to Vote Control for Commitment by ' . Auth::user()->name . ' from Procurement',
            'created_by' => Auth::user()->username,
        ]);

        //Get Vote Control users
        $users = User::voteControl()->get();

        foreach ($users as $user) {
            Notification::send($user, new NotifyVoteControl($vendor));
        }

        $this->refreshVendors();
        $this->dispatch('show-message', message: 'Sent to Vote Control successfully');
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
        if ($name == 'requesting_unit' || $name == 'upload' || $name == 'source_of_funds') {
            $this->skipRender();
        } else {
            $this->dispatch('preserveScroll');
        }
    }

    public function getFormattedDate($date)
    {
        if ($date === null || $date === '') {
            return null;
        }

        try {
            return Carbon::parse($date)->format('F jS, Y');
        } catch (Exception $e) {
            return null;
        }
    }

    public function addVendor()
    {
        $this->vendors[] = $this->newVendorRow();
    }

    public function removeVendor($index)
    {
        unset($this->vendors[$index]);
        $this->vendors = array_values($this->vendors);
    }

    public function toggleAccordionView($index)
    {
        $currentView = $this->vendors[$index]['accordionView'] ?? 'show';
        $this->vendors[$index]['accordionView'] = $currentView === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
    public function getTotalAmountProperty()
    {
        return collect($this->vendors)->sum(function ($vendor) {
            return is_numeric($vendor['amount'] ?? null) ? (float) $vendor['amount'] : 0;
        });
    }

    public function getFirstSavedVendorIdProperty()
    {
        return $this->savedVendorIds()[0] ?? $this->requisition->vendors->first()?->id;
    }

    public function updateVendorStatuses()
    {
        foreach ($this->vendors as &$vendor) {
            $status = 'Awaiting Invoices'; // Default status

            if (!$this->requisition->vote_control_requisition) {
                if (
                    ($vendor['purchase_order_no'] ?? null) &&
                    ($vendor['eta'] ?? null) &&
                    ($vendor['date_sent_commit'] ?? null) &&
                    !($vendor['invoice_no'] ?? null) &&
                    !($vendor['date_invoice_received'] ?? null) &&
                    !($vendor['date_sent_ap'] ?? null)
                ) {
                    $status = 'Awaiting Invoice';
                } elseif (
                    ($vendor['purchase_order_no'] ?? null) &&
                    ($vendor['eta'] ?? null) &&
                    ($vendor['date_sent_commit'] ?? null) &&
                    ($vendor['invoice_no'] ?? null) &&
                    ($vendor['date_invoice_received'] ?? null) &&
                    !($vendor['date_sent_ap'] ?? null)
                ) {
                    $status = 'To Be Sent to AP';
                } elseif (
                    ($vendor['purchase_order_no'] ?? null) &&
                    ($vendor['eta'] ?? null) &&
                    ($vendor['date_sent_commit'] ?? null) &&
                    ($vendor['invoice_no'] ?? null) &&
                    ($vendor['date_invoice_received'] ?? null) &&
                    ($vendor['date_sent_ap'] ?? null) &&
                    !$this->requisition->vote_control_requisition
                ) {
                    $status = 'To Be Sent to AP';
                }
            }

            $vendor['vendor_status'] = $status;
        }
    }


    public function addInvoice($index)
    {
        $this->vendors[$index]['invoices'][] = [
            'invoice_no' => '',
            'invoice_amount' => '0.00',
            'date_invoice_received' => '',
            'date_sent_commit' => '',
            'date_sent_ap' => '',
        ];
    }

    public function displayInvoicesModal($id)
    {
        // dd($id);
        $this->vendor = RequisitionVendor::find($id);
        $this->invoices = $this->vendor->invoices;
        $this->invoice_count = count($this->invoices);
        $this->vendor_name = $this->vendor->vendor_name;
        $this->dispatch('display-invoices-modal');
    }

    public function saveInvoice()
    {
        $this->validate([
            'invoice_no' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($this->vendor->invoices()->where('invoice_no', $value)->exists()) {
                        $fail('The invoice number must be unique for this vendor.');
                    }
                }
            ],
            'invoice_amount' => [
                'required',
                'numeric',
                function ($attribute, $value, $fail) {
                    $totalInvoices = (float) $this->vendor->invoices()->sum('invoice_amount');
                    $newTotal = $totalInvoices + (float) $value;

                    if ($newTotal > (float) $this->vendor->amount) {
                        $fail('The total of all invoices cannot exceed the vendor\'s amount of ' . number_format((float) $this->vendor->amount, 2));
                    }
                }
            ],
            'date_invoice_received' => 'required|bail|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:today',
        ]);

        $this->vendor->invoices()->create([
            'invoice_no' => $this->invoice_no,
            'invoice_amount' => $this->invoice_amount,
            'date_invoice_received' => $this->date_invoice_received,
            'date_sent_commit' => $this->date_sent_commit,
            'date_sent_ap' => $this->date_sent_ap,
        ]);

        $this->invoice_no = null;
        $this->invoice_amount = null;
        $this->date_invoice_received = null;

        $this->invoices = $this->vendor->invoices;
        $this->invoice_count = count($this->invoices);
        $this->dispatch('show-message', message: 'Invoice added successfully');
        // $this->dispatch('refresh-vendors')->to(ViewRequisition::class);
    }

    public function deleteInvoice($id)
    {
        $invoice = $this->vendor->invoices()->find($id);
        $invoice->delete();
        $this->invoices = $this->vendor->invoices;
        $this->invoice_count = count($this->invoices);
        $this->dispatch('show-message', message: 'Invoice deleted successfully');
        // $this->dispatch('refresh-component')->to(ViewRequisition::class);
    }

    public function refreshVendors()
    {
        $this->loadVendors(preserveAccordion: true);
    }

    protected function loadVendors(bool $preserveAccordion = false): void
    {
        $accordionViews = [];

        if ($preserveAccordion) {
            foreach ($this->vendors as $key => $vendor) {
                $accordionViews[$key] = $vendor['accordionView'] ?? 'show';
            }
        }

        $this->vendors = $this->requisition->vendors()
            ->with(['invoices', 'ap', 'votes'])
            ->get()
            ->toArray();

        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = $accordionViews[$key] ?? 'show';
        }
    }

    protected function savedVendorIds(): array
    {
        return collect($this->vendors)->pluck('id')->filter()->values()->all();
    }

    protected function newVendorRow(): array
    {
        return [
            'id' => null,
            'vendor_name' => '',
            'vendor_items' => '',
            'amount' => '',
            'invoices' => [],
            'votes' => [],
            'ap' => null,
            'accordionView' => 'hide',
        ];
    }

    protected function dateFieldNames(): array
    {
        return [
            'date_assigned',
            'date_sent_dps',
            'date_received_procurement',
            'date_sent_aov_procurement',
            'site_visit_date',
            'note_to_ps_date',
            'tender_issue_date',
            'tender_deadline_date',
            'evaluation_start_date',
            'evaluation_end_date',
            'ps_approval_date',
        ];
    }

    protected function nullifyEmptyDates(): void
    {
        foreach ($this->dateFieldNames() as $field) {
            if ($this->{$field} === '') {
                $this->{$field} = null;
            }
        }
    }

    protected function isValidYmdDate($value): bool
    {
        if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return false;
        }

        [$year, $month, $day] = array_map('intval', explode('-', $value));

        return $year >= 1900 && $year <= 9999 && checkdate($month, $day, $year);
    }

    protected function procurement1Rules(): array
    {
        $pastDate = 'nullable|bail|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:today';
        $anyDate = 'nullable|bail|date_format:Y-m-d|after_or_equal:1900-01-01|before_or_equal:9999-12-31';

        return [
            'requisition_no' => 'required',
            'requesting_unit' => 'required',
            'file_no' => 'nullable',
            'item' => 'required',
            'assigned_to' => 'nullable',
            'date_assigned' => $pastDate,
            'date_sent_dps' => $pastDate,
            'date_received_procurement' => $pastDate,
            'date_sent_aov_procurement' => $pastDate,
            'site_visit' => 'nullable|boolean',
            'site_visit_date' => $pastDate,
            'note_to_ps' => 'nullable|boolean',
            'note_to_ps_date' => $pastDate,
            'tender_issue_date' => $anyDate,
            'tender_deadline_date' => $anyDate,
            'evaluation_start_date' => $anyDate,
            'evaluation_end_date' => $anyDate,
            'ps_approval' => 'required',
            'ps_approval_date' => $pastDate,
            'vendors.*.vendor_name' => 'required',
            'vendors.*.vendor_items' => 'nullable|string',
            'vendors.*.amount' => 'required|numeric',
        ];
    }

    protected function procurement1Messages(): array
    {
        return [
            'date_format' => 'Please enter a valid date.',
            'before_or_equal' => 'The :attribute cannot be in the future.',
            'after_or_equal' => 'Please enter a valid date.',
            'vendors.*.vendor_name.required' => 'Vendor name is required',
            'vendors.*.amount.required' => 'Amount is required',
            'vendors.*.amount.numeric' => 'Amount must be a number',
        ];
    }

    protected function procurement1Attributes(): array
    {
        return [
            'date_assigned' => 'date assigned',
            'date_sent_dps' => 'date sent to DPS',
            'date_received_procurement' => 'date received by procurement',
            'date_sent_aov_procurement' => 'date sent to AOV procurement',
            'site_visit_date' => 'site visit date',
            'note_to_ps_date' => 'note to PS date',
            'tender_issue_date' => 'tender issue date',
            'tender_deadline_date' => 'tender deadline date',
            'evaluation_start_date' => 'evaluation start date',
            'evaluation_end_date' => 'evaluation end date',
            'ps_approval_date' => 'PS approval date',
        ];
    }

    public function getIsSendAllToVoteControlButtonDisabledProperty()
    {
        $savedVendors = collect($this->vendors)->filter(fn ($vendor) => !empty($vendor['id']));

        if ($savedVendors->isEmpty()) {
            return true;
        }

        foreach ($savedVendors as $vendor) {
            if ($this->isProcurement2ButtonDisabled($vendor)) {
                return true;
            }
        }

        return false;
    }

    public function cancelRequisition()
    {
        $this->validate([
            'cancelation_reason' => 'required|string|min:10',
        ], [
            'cancelation_reason.required' => 'Please provide a reason for cancellation.',
            'cancelation_reason.min' => 'The cancellation reason must be at least 10 characters.',
        ]);

        if (Gate::denies('cancel-requisition')) {
            $this->dispatch('show-message', message: 'You do not have permission to cancel requisitions.');
            return;
        }

        $this->requisition->update([
            'requisition_status' => RequestFormStatus::CANCELED,
            'is_completed' => false,
            'cancelation_reason' => $this->cancelation_reason,
            'updated_by' => Auth::user()->username,
        ]);

        if ($this->requisition->requisitionForm) {

            $this->requisition->requisitionForm->logs()->create([
                'details' => 'Requisition #' . $this->requisition->requisition_no . ' was canceled by ' . Auth::user()->name . '. Reason: ' . $this->cancelation_reason,
                'created_by' => Auth::user()->username,
            ]);
        }

        $this->requisition->statuslogs()->create([
            'details' => 'Requisition #' . $this->requisition->requisition_no . ' was canceled by ' . Auth::user()->name . '. Reason: ' . $this->cancelation_reason,
            'created_by' => Auth::user()->username,
        ]);

        Log::info('Requisition #' . $this->requisition->requisition_no . ' was canceled by ' . Auth::user()->username, [
            'requisition_id' => $this->requisition->id,
            'requisition_no' => $this->requisition->requisition_no,
            'url' => route('requisitions.view', $this->requisition->id, absolute: true),
        ]);

        $notifiableUsers = collect();

        if ($this->requisition->requisitionForm) {
            $headOfDepartment = $this->requisition->requisitionForm->requestingUnit->headOfDepartment;
            $contactPerson = $this->requisition->requisitionForm->contactPerson;
            $reportingOfficer = $this->requisition->requisitionForm->reportingOfficer;

            if ($headOfDepartment) {
                $notifiableUsers->push($headOfDepartment);
            }

            if ($contactPerson && $contactPerson->id !== $headOfDepartment?->id) {
                $notifiableUsers->push($contactPerson);
            }

            if ($reportingOfficer) {
                $notifiableUsers->push($reportingOfficer);
            }
        }

        foreach ($notifiableUsers->unique('id') as $user) {
            Notification::send($user, new \App\Notifications\RequisitionCanceled($this->requisition));
        }

        $this->dispatch('close-cancel-modal');
        $this->dispatch('show-message', message: 'Requisition canceled successfully.');
        $this->requisition_status = RequestFormStatus::CANCELED;
        $this->requisition = $this->requisition->fresh();
    }
}
