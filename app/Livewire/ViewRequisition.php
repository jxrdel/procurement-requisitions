<?php

namespace App\Livewire;

use App\Mail\ErrorNotification;
use App\Mail\NotifyAccountsPayable;
use App\Mail\NotifyCostBudgeting;
use App\Mail\NotifyVoteControl;
use App\Models\CBRequisition;
use App\Models\Cheque;
use App\Models\Department;
use App\Models\Requisition;
use App\Models\RequisitionVendor;
use App\Models\Status;
use App\Models\User;
use App\Models\VendorInvoice;
use App\Models\Vote;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
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
    public $date_received_procurement;
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
    public $deleted_vendors = [];

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

    public $departments;
    public $staff;
    public $logs;
    public $votes;


    public function render()
    {
        $this->invoice_count_buttons = [];

        // Populate invoiceCounts dynamically
        foreach ($this->vendors as $vendor) {
            $this->invoice_count_buttons[$vendor['id']] = VendorInvoice::where('vendor_id', $vendor['id'])->count();
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
        if (!$this->requisition) {
            return abort(404);
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
        $this->date_sent_dps = $this->requisition->date_sent_dps;
        $this->ps_approval = $this->requisition->ps_approval;
        $this->amount = $this->requisition->amount;
        $this->denied_note = $this->requisition->denied_note;
        $this->ps_approval_date = $this->requisition->ps_approval_date;
        $this->sent_to_cb = $this->requisition->sent_to_cb;
        $this->date_sent_cb = $this->requisition->date_sent_cb;
        $this->date_completed = $this->requisition->date_completed;
        $this->vendors = $this->requisition->vendors()
            ->with('invoices')
            ->with('ap')
            ->select(
                'id',
                'vendor_name',
                'amount',
                'vendor_status',

                //Procurement
                'purchase_order_no',
                'eta',
                'date_sent_commit',
                'invoice_no',
                'date_invoice_received',
                'date_sent_ap',
                'sent_to_ap',

                // Cost & Budgeting
                'date_sent_request_mof',
                'release_type',
                'request_category',
                'request_no',
                'release_no',
                'release_date',
                'change_of_vote_no',

                // AP
                'date_received_ap',
                'date_sent_vc',

                // Vote Control
                'batch_no',
                'voucher_no',
                'date_sent_checkstaff',

                // Check Staff
                'date_received_from_vc',
                'voucher_destination',
                'date_sent_audit',
                'date_received_from_audit',
                'date_sent_chequeprocessing',

                // Cheque Processing
                'date_of_cheque',
                'cheque_no',
                'date_cheque_processed',
                'date_sent_dispatch',
            )->get()
            ->toArray();

        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = 'show';
        }

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
        try {
            if ($this->date_assigned === '') {
                $this->date_assigned = null;
            }

            if ($this->date_sent_dps === '') {
                $this->date_sent_dps = null;
            }

            if ($this->date_received_procurement === '') {
                $this->date_received_procurement = null;
            }

            if (!$this->validateForm()) {
                return;  // Stop execution if form validation fails
            }

            Requisition::where('id', $this->requisition->id)->update([
                'requisition_no' => $this->requisition_no,
                'requesting_unit' => $this->requesting_unit,
                'file_no' => $this->file_no,
                'item' => $this->item,
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

            if (!$this->requisition->cost_budgeting_requisition) {
                $this->setRequisitionStatus();
            }

            foreach ($this->deleted_vendors as $id) {
                $this->requisition->vendors()->where('id', $id)->delete();
            }

            foreach ($this->vendors as $index => $vendor) {
                if (isset($vendor['id'])) {
                    $this->requisition->vendors()->where('id', $vendor['id'])->update([
                        'vendor_name' => $vendor['vendor_name'],
                        'amount' => $vendor['amount'],
                    ]);
                } else {
                    $newvendor = $this->requisition->vendors()->create([
                        'vendor_name' => $vendor['vendor_name'],
                        'amount' => $vendor['amount'],
                    ]);
                    $this->vendors[$index]['id'] = $newvendor->id;
                }
            }

            Log::info('Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->username);
            $this->isEditingProcurement1 = false;
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Requisition edited successfully');
            $this->requisition = $this->requisition->fresh();
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
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
                'date_sent_procurement' => $this->date_received_procurement,
                'ps_approval' => $this->ps_approval,
                // 'sent_to_cb' => $this->sent_to_cb,
                // 'date_sent_cb' => $this->date_sent_cb,
                'vendors' => $this->vendors,
            ],
            [
                'requisition_no' => 'required',
                'requesting_unit' => 'required',
                'file_no' => 'nullable',
                'item' => 'required',
                'assigned_to' => 'nullable',
                'date_assigned' => 'nullable|date|before_or_equal:today',
                'date_sent_dps' => 'nullable|date|before_or_equal:today',
                'date_received_procurement' => 'nullable|date|before_or_equal:today',
                // 'date_sent_cb' => 'nullable|sometimes|after:date_sent_dps',
                'vendors.*.vendor_name' => 'required',
                'vendors.*.amount' => 'required|numeric',
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
        return $this->requisition_no === null || trim($this->requisition_no) === '' ||
            $this->requesting_unit === null || trim($this->requesting_unit) === '' ||
            $this->item === null || trim($this->item) === '' ||
            $this->date_sent_dps === null || trim($this->date_sent_dps) === '' ||
            $this->ps_approval === null || $this->ps_approval !== 'Approved' || count($this->requisition->vendors) === 0;
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

        //Get Cost & Budgeting users
        $users = User::costBudgeting()->get();

        foreach ($users as $user) {
            Mail::to($user->email)->queue(new NotifyCostBudgeting($this->requisition));
            // Log::info('Email sent to ' . $user->email . ' from ' . Auth::user()->name . ' for Requisition #' . $this->requisition->requisition_no);
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
        if ($vendor['vendor_status'] === '') {
            return true;
        }

        $totalInvoiceAmount = collect($vendor['invoices'])->sum('invoice_amount');

        return
            empty(trim($vendor['purchase_order_no'])) ||
            empty($vendor['eta']) ||
            empty($vendor['date_sent_ap']) ||
            empty($vendor['invoices']) ||
            $totalInvoiceAmount != $vendor['amount']; // Ensure invoice amounts add up to the vendor amount
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
            if ($vendor['eta'] === '') {
                $vendor['eta'] = null;
            }

            if ($vendor['date_sent_commit'] === '') {
                $vendor['date_sent_commit'] = null;
            }

            if ($vendor['date_invoice_received'] === '') {
                $vendor['date_invoice_received'] = null;
            }

            if ($vendor['date_sent_ap'] === '') {
                $vendor['date_sent_ap'] = null;
            }
        }

        unset($vendor);

        $this->updateVendorStatuses();

        $this->validate(
            [
                'vendors.*.eta' => 'nullable|date|after_or_equal:today',
                'vendors.*.date_sent_commit' => 'nullable|date|after_or_equal:' . $this->requisition->date_sent_dps,
            ],
            [
                'vendors.*.eta.after_or_equal' => 'ETA must be a date after or equal to today',
                'vendors.*.date_sent_commit.after_or_equal' => 'Date sent to Commitment must be a date after or equal to the date sent to DPS',
            ]
        );

        try {
            foreach ($this->vendors as $vendor) {
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

            // $this->setRequisitionStatus();

            Log::info('Requisition #' . $this->requisition->requisition_no . ' was edited by ' . Auth::user()->username);
            $this->isEditingProcurement2 = false;
            $this->refreshVendors();
            $this->resetValidation();
            $this->dispatch('show-message', message: 'Requisition edited successfully');
        } catch (Exception $e) {
            Log::error('Error from user ' . Auth::user()->username . ' while editing a requisition: ' . $e->getMessage());
            Mail::to('jardel.regis@health.gov.tt')->queue(new ErrorNotification(Auth::user()->username, $e->getMessage()));
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

        $vendor->ap()->create([
            'date_received' => Carbon::now(),
        ]);

        $this->requisition->update([
            'requisition_status' => 'Sent to Accounts Payable',
            'updated_by' => Auth::user()->username,
        ]);

        // $this->requisition->ap_requisition()->create([
        //     'date_received' => Carbon::now(),
        // ]);

        //Send email to Accounts Payable
        Log::info('Vendor ' . $vendor->vendor_name . ' for requisition #' . $this->requisition->requisition_no . ' was sent to Accounts Payable by ' . Auth::user()->username);

        //Get Accounts Payable users
        $users = User::accountsPayable()->get();

        foreach ($users as $user) {
            Mail::to($user->email)->send(new NotifyAccountsPayable($vendor));
            // Log::info('Email sent to ' . $user->email . ' from ' . Auth::user()->name . ' for Requisition #' . $this->requisition->requisition_no);
        }

        // return redirect()->route('requisitions.view', ['id' => $this->requisition->id])->with('success', 'Requisition sent to Accounts Payable');

        $this->refreshVendors();

        $this->dispatch('show-message', message: 'Sent to AP successfully');
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
        if ($date !== null) {
            return Carbon::parse($date)->format('F jS, Y');
        }
    }

    public function addVendor()
    {
        $this->vendors[] = [
            'id' => null,
            'vendor_name' => '',
            'amount' => '0.00',

            // Procurement
            'vendor_status' => '',
            'purchase_order_no' => '',
            'eta' => null,
            'date_sent_commit' => null,
            'invoice_no' => '',
            'date_invoice_received' => null,
            'date_sent_ap' => null,
            'sent_to_ap' => false,

            // Cost & Budgeting
            'date_sent_request_mof' => null,
            'release_type' => '',
            'request_category' => '',
            'request_no' => '',
            'release_no' => '',
            'release_date' => null,
            'change_of_vote_no' => '',

            // AP
            'date_received_ap' => null,
            'date_sent_vc' => null,

            // Vote Control
            'batch_no' => '',
            'voucher_no' => '',
            'date_sent_checkstaff' => null,

            // Check Staff
            'date_received_from_vc' => null,
            'voucher_destination' => '',
            'date_sent_audit' => null,
            'date_received_from_audit' => null,
            'date_sent_chequeprocessing' => null,

            // Cheque Processing
            'date_of_cheque' => null,
            'cheque_no' => '',
            'date_cheque_processed' => null,
            'date_sent_dispatch' => null,

            'accordionView' => 'hide',
        ];
    }

    public function removeVendor($index)
    {
        // dd($this->vendors[$index]);
        if (isset($this->vendors[$index]['id'])) {
            $this->deleted_vendors[] = $this->vendors[$index]['id'];
        }
        unset($this->vendors[$index]);
    }

    public function toggleAccordionView($index)
    {
        $this->vendors[$index]['accordionView'] = $this->vendors[$index]['accordionView'] === 'show' ? 'hide' : 'show';

        $this->skipRender();
    }
    public function getTotalAmountProperty()
    {
        return collect($this->vendors)->sum('amount');
    }
    // public function getProcurement2Status()
    // {
    //     $priority = [
    //         'Awaiting Invoice' => 1,
    //         'To Be Sent to AP' => 2,
    //     ];

    //     $statuses = collect($this->vendors)->map(function ($vendor) {
    //         if (
    //             $vendor['purchase_order_no'] && $vendor['eta'] && $vendor['date_sent_commit'] &&
    //             !$vendor['invoice_no'] && !$vendor['date_invoice_received'] && !$vendor['date_sent_ap']
    //         ) {
    //             return 'Awaiting Invoice';
    //         }

    //         if (
    //             $vendor['purchase_order_no'] && $vendor['eta'] && $vendor['date_sent_commit'] &&
    //             $vendor['invoice_no'] && $vendor['date_invoice_received'] && !$vendor['date_sent_ap']
    //         ) {
    //             return 'To Be Sent to AP';
    //         }

    //         if (
    //             $vendor['purchase_order_no'] && $vendor['eta'] && $vendor['date_sent_commit'] &&
    //             $vendor['invoice_no'] && $vendor['date_invoice_received'] &&
    //             $vendor['date_sent_ap'] && !$this->requisition->vote_control_requisition
    //         ) {
    //             return 'To Be Sent to AP';
    //         }

    //         return 'At Procurement'; // Default case if none of the conditions match
    //     });

    //     return $statuses->sortBy(fn($status) => $priority[$status] ?? PHP_INT_MAX)->first();
    // }

    public function updateVendorStatuses()
    {
        foreach ($this->vendors as &$vendor) {
            $status = 'Awaiting Invoices'; // Default status

            if (!$this->requisition->vote_control_requisition) {
                if (
                    $vendor['purchase_order_no'] &&
                    $vendor['eta'] &&
                    $vendor['date_sent_commit'] &&
                    !$vendor['invoice_no'] &&
                    !$vendor['date_invoice_received'] &&
                    !$vendor['date_sent_ap']
                ) {
                    $status = 'Awaiting Invoice';
                } elseif (
                    $vendor['purchase_order_no'] &&
                    $vendor['eta'] &&
                    $vendor['date_sent_commit'] &&
                    $vendor['invoice_no'] &&
                    $vendor['date_invoice_received'] &&
                    !$vendor['date_sent_ap']
                ) {
                    $status = 'To Be Sent to AP';
                } elseif (
                    $vendor['purchase_order_no'] &&
                    $vendor['eta'] &&
                    $vendor['date_sent_commit'] &&
                    $vendor['invoice_no'] &&
                    $vendor['date_invoice_received'] &&
                    $vendor['date_sent_ap'] &&
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
                    $totalInvoices = $this->vendor->invoices()->sum('invoice_amount');
                    $newTotal = $totalInvoices + $value;

                    if ($newTotal > $this->vendor->amount) {
                        $fail('The total of all invoices cannot exceed the vendor\'s amount of ' . number_format($this->vendor->amount, 2));
                    }
                }
            ],
            'date_invoice_received' => 'required|date',
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

    public function getInvoiceCounts($index)
    {
        $vendorId = $this->vendors[$index]['id'];
        $this->invoiceCounts[$vendorId] = VendorInvoice::where('vendor_id', $vendorId)->count();
    }

    public function refreshVendors()
    {

        //Get accordion view state
        $accordionView = collect($this->vendors)->map(function ($vendor) {
            return $vendor['accordionView'];
        });

        $this->vendors = $this->requisition->vendors()
            ->with('invoices')
            ->with('ap')
            ->select(
                'id',
                'vendor_name',
                'amount',
                'vendor_status',

                //Procurement
                'purchase_order_no',
                'eta',
                'date_sent_commit',
                'invoice_no',
                'date_invoice_received',
                'date_sent_ap',
                'sent_to_ap',

                // Cost & Budgeting
                'date_sent_request_mof',
                'release_type',
                'request_category',
                'request_no',
                'release_no',
                'release_date',
                'change_of_vote_no',

                // AP
                'date_received_ap',
                'date_sent_vc',

                // Vote Control
                'batch_no',
                'voucher_no',
                'date_sent_checkstaff',

                // Check Staff
                'date_received_from_vc',
                'voucher_destination',
                'date_sent_audit',
                'date_received_from_audit',
                'date_sent_chequeprocessing',

                // Cheque Processing
                'date_of_cheque',
                'cheque_no',
                'date_cheque_processed',
                'date_sent_dispatch',
            )->get()
            ->toArray();

        foreach ($this->vendors as $key => $vendor) {
            $this->vendors[$key]['accordionView'] = $accordionView[$key];
        }
    }
}
