<?php

namespace App\Livewire;

use App\FormCategory;
use App\Models\Department;
use App\Models\RequisitionRequestForm;
use App\Models\User;
use App\Models\Vote;
use App\Notifications\ApprovedByProcurement;
use App\Notifications\ApprovedByReportingOfficer;
use App\Notifications\DeclinedByHOD;
use App\Notifications\DeclinedByProcurement;
use App\Notifications\DeclinedByReportingOfficer;
use App\Notifications\ForwardForm;
use App\Notifications\ApprovedByCAB;
use App\Notifications\RequestForCABApproval;
use App\Notifications\RequestForFurtherApproval;
use App\Notifications\RequestForHODApproval;
use App\Notifications\RequestForProcurementApproval;
use App\Notifications\RequestForReportingOfficerApproval;
use App\RequestFormStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Route;

class ViewRequisitionForm extends Component
{
    use WithFileUploads;

    public RequisitionRequestForm $requisitionForm;
    public $backUrl;

    #[Title('View Requisition Form | PRA')]

    public $requesting_unit;
    public $head_of_department;
    public $contact_person_id;
    public $date;
    public $category;
    public $contact_info;

    public $justification;
    public $location_of_delivery;
    public $date_required_by;
    public $estimated_value;

    public $availability_of_funds;
    public $verified_by_accounts;
    public $vote_no;
    public $seen_by;
    public $procurement_officer_assigned;
    public $date_received;
    public $expected_date_of_completion;
    public $contact_person_note;
    public $hod_note;
    public $reporting_officer_note;
    public $cab_note;

    public $units;
    public $users;

    public $items = [];
    public $validationErrors = [];
    public $selected_votes = [];
    public $item_name;
    public $qty_in_stock = 0;
    public $qty_requesting = 1;
    public $unit_of_measure;
    public $size;
    public $colour;
    public $brand_model;
    public $other;
    public $editItemKey;

    public $uploads;
    public $votes;
    public $categories;

    public $isEditing = false;
    public $isCabEditing = false;

    public $details;

    public $forwardedOfficer;
    public $forwarding_minute;

    #[Computed]
    public function sendToHodDisabled()
    {
        if (Gate::denies('send-form-to-hod', $this->requisitionForm)) {
            return true;
        }
        return !$this->availability_of_funds || !$this->verified_by_accounts || empty($this->selected_votes) || !$this->requisitionForm->completed_by_cab;
    }

    #[Computed]
    public function sortedLogs()
    {
        return $this->requisitionForm->logs()->latest()->get();
    }

    //HOD Approval/Denial Modal
    public $selectedOfficer;
    public $reportingOfficers;
    public $forwardingOfficers;
    public $declineReason;

    public function mount($id)
    {

        $this->requisitionForm = RequisitionRequestForm::with('items')->findOrFail($id);
        if (Gate::denies('view-requisition-form', $this->requisitionForm)) {
            abort(403, 'You do not have permission to view this requisition form.');
        }

        if (Route::currentRouteName() == 'queue.form.view') {
            $this->backUrl = route('queue');
        } else {
            $this->backUrl = route('requisition_forms.index');
        }

        $this->units = Department::orderBy('name')->get();
        $this->users = User::orderBy('name')->get();
        $this->votes = Vote::orderBy('number')->get();
        $this->categories = FormCategory::options();

        $this->requesting_unit = $this->requisitionForm->requesting_unit;
        $this->head_of_department = $this->requisitionForm->head_of_department_id;
        $this->contact_person_id = $this->requisitionForm->contact_person_id;
        $this->date = $this->requisitionForm->date ? $this->requisitionForm->date->format('Y-m-d') : null;
        $this->category = $this->requisitionForm->category;
        $this->contact_info = $this->requisitionForm->contact_info;
        $this->justification = $this->requisitionForm->justification;
        $this->location_of_delivery = $this->requisitionForm->location_of_delivery;
        $this->date_required_by = $this->requisitionForm->date_required_by ? $this->requisitionForm->date_required_by->format('Y-m-d') : null;
        $this->estimated_value = $this->requisitionForm->estimated_value;
        $this->availability_of_funds = $this->requisitionForm->availability_of_funds;
        $this->verified_by_accounts = $this->requisitionForm->verified_by_accounts;
        $this->vote_no = $this->requisitionForm->vote_no;
        $this->cab_note = $this->requisitionForm->cab_note;

        $this->items = $this->requisitionForm->items->toArray();
        $this->selected_votes = $this->requisitionForm->votes()->pluck('vote_id')->toArray();

        $excludedOfficerIds = array_filter([
            Auth::id(),
            $this->requisitionForm->reporting_officer_id,
            $this->requisitionForm->second_reporting_officer_id,
            $this->requisitionForm->third_reporting_officer_id,
        ]);

        $this->reportingOfficers = User::reportingOfficers()
            ->whereNotIn('id', array_unique($excludedOfficerIds))
            ->orderByDesc('reporting_officer_role')
            ->get();

        if ($this->requisitionForm->status === RequestFormStatus::SENT_TO_HOD) {
            $this->reportingOfficers = User::reportingOfficers()->orderByDesc('reporting_officer_role')->get();
        }

        $this->forwardingOfficers = User::reportingOfficers()->where('id', '!=', Auth::id())->orderBy('name')->get();
    }

    public function save()
    {
        // Build validation rules for items dynamically
        $rules = [
            'requesting_unit' => 'required|exists:departments,id',
            'head_of_department' => 'required|exists:users,id',
            'contact_person_id' => 'required|exists:users,id',
            'date' => 'required|date|date_format:Y-m-d',
            'contact_info' => 'nullable|string|max:255',
            'justification' => 'required|string',
            'location_of_delivery' => 'nullable|string|max:255',
            'date_required_by' => 'nullable|date|after_or_equal:date|date_format:Y-m-d',
            'estimated_value' => 'nullable|numeric|min:0|max:99999999.99',
            'items' => 'required|array|min:1',
        ];

        foreach ($this->items as $index => $item) {
            $rules["items.{$index}.name"] = 'required|string';
            $rules["items.{$index}.qty_in_stock"] = 'required|integer|min:0';
            $rules["items.{$index}.qty_requesting"] = 'required|integer|min:1';
            $rules["items.{$index}.unit_of_measure"] = 'nullable|string|max:50';
            $rules["items.{$index}.size"] = 'nullable|string|max:50';
            $rules["items.{$index}.colour"] = 'nullable|string|max:50';
            $rules["items.{$index}.brand_model"] = 'nullable|string|max:255';
            $rules["items.{$index}.other"] = 'nullable|string|max:255';
        }

        try {
            $this->validate($rules, [
                'uploads.required' => 'Please upload at least 1 document.',
                'items.*.name.required' => 'This field is required.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->validator->errors()->toArray();
            $this->dispatch('scrollToError');
            throw $e;
        }

        try {
            DB::transaction(function () {

                $data = [
                    'requesting_unit' => $this->requesting_unit,
                    'head_of_department_id' => $this->head_of_department,
                    'contact_person_id' => $this->contact_person_id,
                    'date' => $this->date,
                    'category' => $this->category,
                    'contact_info' => $this->contact_info,
                    'justification' => $this->justification,
                    'location_of_delivery' => $this->location_of_delivery,
                    'date_required_by' => $this->date_required_by,
                    'estimated_value' => $this->estimated_value,
                    'availability_of_funds' => $this->availability_of_funds,
                    'verified_by_accounts' => $this->verified_by_accounts,
                    'vote_no' => $this->vote_no,
                ];

                $this->requisitionForm->update($data);

                $this->requisitionForm->items()->delete();
                $this->requisitionForm->items()->createMany($this->items);

                $this->requisitionForm->votes()->sync($this->selected_votes);

                $this->requisitionForm->logs()->create([
                    'details' => 'Requisition form edited by ' . Auth::user()->name,
                    'created_by' => Auth::user()->username ?? null,
                ]);
            });

            $this->dispatch('show-message', message: 'Form updated successfully.');
            $this->isEditing = false;
        } catch (\Throwable $e) {

            Log::error('Failed to update requisition form', [
                'error' => $e->getMessage(),
                'user' => Auth::user()->username ?? null,
                'form_id' => $this->requisitionForm->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            $this->dispatch(
                'show-error',
                message: 'An error occurred while updating the form. Please contact ICT Helpdesk at ext 11000.'
            );
        }
    }


    public function displayEditModal($key)
    {
        $this->editItemKey = $key;
        $item = $this->items[$key];

        $this->item_name = $item['name'];
        $this->qty_in_stock = $item['qty_in_stock'];
        $this->qty_requesting = $item['qty_requesting'];
        $this->unit_of_measure = $item['unit_of_measure'];
        $this->size = $item['size'];
        $this->colour = $item['colour'];
        $this->brand_model = $item['brand_model'];
        $this->other = $item['other'];

        $this->dispatch('display-edit-item-modal');
    }

    public function editItem()
    {
        $this->validate([
            'item_name' => 'required|string|max:255',
            'qty_in_stock' => 'required|integer|min:0',
            'qty_requesting' => 'required|integer|min:1',
            'unit_of_measure' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'colour' => 'nullable|string|max:50',
            'brand_model' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);

        $this->items[$this->editItemKey] = [
            'name' => $this->item_name,
            'qty_in_stock' => $this->qty_in_stock,
            'qty_requesting' => $this->qty_requesting,
            'unit_of_measure' => $this->unit_of_measure,
            'size' => $this->size,
            'colour' => $this->colour,
            'brand_model' => $this->brand_model,
            'other' => $this->other,
        ];

        $this->dispatch('show-message', message: 'Item updated successfully');
        $this->dispatch('close-edit-item-modal');

        $this->reset([
            'item_name',
            'qty_in_stock',
            'qty_requesting',
            'unit_of_measure',
            'size',
            'colour',
            'brand_model',
            'other'
        ]);
    }

    public function addItem()
    {
        $this->validate([
            'item_name' => 'required|string|max:255',
            'qty_in_stock' => 'required|integer|min:0',
            'qty_requesting' => 'required|integer|min:1',
            'unit_of_measure' => 'nullable|string|max:50',
            'size' => 'nullable|string|max:50',
            'colour' => 'nullable|string|max:50',
            'brand_model' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
        ]);

        $newItem = [
            'name' => $this->item_name,
            'qty_in_stock' => $this->qty_in_stock,
            'qty_requesting' => $this->qty_requesting,
            'unit_of_measure' => $this->unit_of_measure,
            'size' => $this->size,
            'colour' => $this->colour,
            'brand_model' => $this->brand_model,
            'other' => $this->other,
        ];

        $this->items[] = $newItem;
        $this->dispatch('close-add-item-modal');

        $this->reset([
            'item_name',
            'qty_in_stock',
            'qty_requesting',
            'unit_of_measure',
            'size',
            'colour',
            'brand_model',
            'other'
        ]);
    }

    public function removeItem($key)
    {
        unset($this->items[$key]);
    }

    public function updating($name, $value)
    {
        if ($name == 'requesting_unit') {
            $this->skipRender();
        } else {
            $this->dispatch('preserveScroll');
        }
    }

    public function sendToHOD()
    {
        $this->validate([
            'contact_person_note' => 'required|string',
        ]);

        $this->requisitionForm->status = RequestFormStatus::SENT_TO_HOD;
        $this->requisitionForm->contact_person_note = $this->contact_person_note;
        $this->requisitionForm->date_sent_to_hod = now();
        $this->requisitionForm->save();

        $hod = $this->requisitionForm->headOfDepartment;
        if ($hod) {
            Notification::send($hod, new RequestForHODApproval($this->requisitionForm));
        }

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form sent to Head of Department for approval by ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);

        $this->dispatch('close-sent-to-hod-modal');
        $this->dispatch('show-message', message: 'Requisition form sent to Head of Department for approval.');
    }

    public function saveLog()
    {
        $this->validate([
            'details' => 'required',
        ]);

        $this->requisitionForm->logs()->create([
            'details' => $this->details,
            'created_by' => Auth::user()->username,
        ]);

        $this->reset('details');

        $this->dispatch('close-log-modal');
        $this->dispatch('show-message', message: 'Log added successfully.');
    }

    public function approveRequisitionHOD()
    {
        $this->validate([
            'selectedOfficer' => 'required|exists:users,id',
            'hod_note' => 'required|string',
        ], [
            'selectedOfficer.required' => 'Please select a Reporting Officer to send the form to.',
            'selectedOfficer.exists' => 'The selected Reporting Officer is invalid.',
            'hod_note.required' => 'Please enter a minute',
        ]);

        $reportingOfficer = User::find($this->selectedOfficer);
        if ($reportingOfficer->reporting_officer_role == 'Permanent Secretary') {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_PS;
            $this->requisitionForm->sent_to_ps = true;
        } elseif ($reportingOfficer->reporting_officer_role == 'Deputy Permanent Secretary') {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_DPS;
            $this->requisitionForm->sent_to_dps = true;
        } elseif ($reportingOfficer->reporting_officer_role == 'Chief Medical Officer') {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_CMO;
            $this->requisitionForm->sent_to_cmo = true;
        }

        $this->requisitionForm->hod_approval = true;
        $this->requisitionForm->hod_note = $this->hod_note;
        $this->requisitionForm->hod_approval_date = now();
        $this->requisitionForm->hod_digital_signature = Auth::user()->digital_signature;
        $this->requisitionForm->reporting_officer_id = $this->selectedOfficer;
        $this->requisitionForm->save();

        if ($reportingOfficer) {
            Notification::send($reportingOfficer, new RequestForReportingOfficerApproval($this->requisitionForm));
        }

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form approved by ' . Auth::user()->name . ' and sent to ' . $reportingOfficer->reporting_officer_role . ' ' . $reportingOfficer->name . ' for non-objection.',
            'created_by' => Auth::user()->username,
        ]);

        $this->reset('selectedOfficer');
        $this->dispatch('close-hod-approval-modal');
        $this->dispatch('show-message', message: 'Requisition form approved and sent to Reporting Officer for approval.');
    }

    public function approveRequisitionReportingOfficer()
    {
        $specialDepartments = [
            'Office of the Chief Medical Officer',
            'Office of the Deputy Permanent Secretary',
            'Office of the Permanent Secretary'
        ];

        if (
            $this->requisitionForm->status == RequestFormStatus::SENT_TO_HOD &&
            in_array($this->requisitionForm->requestingUnit->name, $specialDepartments)
        ) {
            $this->requisitionForm->reporting_officer_id = Auth::user()->id;
            $this->requisitionForm->hod_approval = true;
            $this->requisitionForm->hod_approval_date = now();
            $this->requisitionForm->hod_digital_signature = Auth::user()->digital_signature;
        }

        $this->requisitionForm->reporting_officer_approval = true;
        $this->requisitionForm->reporting_officer_approval_date = now();
        $this->requisitionForm->reporting_officer_digital_signature = Auth::user()->digital_signature;
        $this->requisitionForm->status = RequestFormStatus::SENT_TO_PROCUREMENT;
        $this->requisitionForm->save();

        $procurementHOD = User::where('name', 'Maryann Basdeo')->first();
        if ($procurementHOD) {
            Notification::send($procurementHOD, new ApprovedByReportingOfficer($this->requisitionForm));
        }

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form received non-objection from ' . Auth::user()->name . ' and sent to Procurement.',
            'created_by' => Auth::user()->username,
        ]);

        $this->dispatch('close-ro-approval-modal');
        $this->dispatch('show-message', message: 'Form sent to Procurement successfully.');
    }

    public function approveRequisitionProcurement()
    {
        $this->requisitionForm->status = RequestFormStatus::APPROVED_BY_PROCUREMENT;
        $this->requisitionForm->procurement_approval = true;
        $this->requisitionForm->procurement_approval_date = now();
        $this->requisitionForm->procurement_digital_signature = Auth::user()->digital_signature;
        $this->requisitionForm->save();

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form approved by Procurement Officer ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);

        Notification::send($this->requisitionForm->headOfDepartment, new ApprovedByProcurement($this->requisitionForm));


        $this->dispatch('show-message', message: 'Requisition form approved successfully.');
    }


    public function declineRequisition()
    {
        $this->validate([
            'declineReason' => 'required',
        ]);

        if (Auth::user()->id == $this->requisitionForm->head_of_department_id) {
            $this->requisitionForm->status = RequestFormStatus::DENIED_BY_HOD;
            $this->requisitionForm->hod_approval = false;
            $this->requisitionForm->hod_reason_for_denial = $this->declineReason;
            //Reset approval flags
            Notification::send($this->requisitionForm->contactPerson, new DeclinedByHOD($this->requisitionForm));
        }

        if (Auth::user()->id == $this->requisitionForm->reporting_officer_id && Auth::user()->is_reporting_officer) {
            //Change status based on role
            if (Auth::user()->reporting_officer_role == 'Permanent Secretary') {
                $this->requisitionForm->status = RequestFormStatus::DENIED_BY_PS;
            } elseif (Auth::user()->reporting_officer_role == 'Deputy Permanent Secretary') {
                $this->requisitionForm->status = RequestFormStatus::DENIED_BY_DPS;
            } elseif (Auth::user()->reporting_officer_role == 'Chief Medical Officer') {
                $this->requisitionForm->status = RequestFormStatus::DENIED_BY_CMO;
            }

            $this->requisitionForm->reporting_officer_approval = false;
            $this->requisitionForm->reporting_officer_reason_for_denial = $this->declineReason;
            //Reset approval flags
            Notification::send($this->requisitionForm->contactPerson, new DeclinedByReportingOfficer($this->requisitionForm));
        }

        if (Auth::user()->department->name == 'Procurement Unit') {
            $this->requisitionForm->status = RequestFormStatus::DENIED_BY_PROCUREMENT;
            $this->requisitionForm->procurement_approval = false;
            $this->requisitionForm->procurement_reason_for_denial = $this->declineReason;
            Notification::send($this->requisitionForm->contactPerson, new DeclinedByProcurement($this->requisitionForm));
        }

        $this->requisitionForm->save();

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form declined by ' . Auth::user()->name . '. Reason: ' . $this->declineReason,
            'created_by' => Auth::user()->username,
        ]);

        $this->reset('declineReason');

        $this->dispatch('close-decline-modal');
        $this->dispatch('show-message', message: 'Requisition form declined successfully.');
    }

    public function sendToProcurement()
    {
        $this->requisitionForm->status = RequestFormStatus::SENT_TO_PROCUREMENT;
        $this->requisitionForm->save();

        // Get user where the name is Marryann Basdeo
        $procurementHOD = User::where('name', 'Maryann Basdeo')->first();
        if ($procurementHOD) {
            Notification::send($procurementHOD, new RequestForProcurementApproval($this->requisitionForm));
        }

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form sent to Procurement by ' . Auth::user()->name,
            'created_by' => Auth::user()->username,
        ]);

        $this->dispatch('show-message', message: 'Requisition form sent to Procurement successfully.');
    }

    public function uploadFiles()
    {
        $this->validate([
            'uploads.*' => 'file|max:100240',
        ], [
            'uploads.*.max' => 'Each file must not exceed 5MB in size.',
        ]);

        if ($this->uploads) {
            foreach ($this->uploads as $upload) {
                $filename = $upload->getClientOriginalName();
                $uploadPath = $upload->store('file_uploads', 'public');
                $this->requisitionForm->uploads()->create([
                    'file_name' => $filename,
                    'file_path' => $uploadPath,
                    'uploaded_by' => Auth::user()->name ?? null,
                ]);

                // Add log
                $this->requisitionForm->logs()->create([
                    'details' => 'File "' . $filename . '" uploaded by ' . Auth::user()->name,
                    'created_by' => Auth::user()->username ?? null,
                ]);
            }
        }

        $this->reset('uploads');

        $this->dispatch('show-message', message: 'Files uploaded successfully.');
    }

    public function deleteFile($fileId)
    {
        $file = $this->requisitionForm->uploads()->find($fileId);
        if ($file) {
            //Add log
            $this->requisitionForm->logs()->create([
                'details' => 'File "' . $file->file_name . '" deleted by ' . Auth::user()->name,
                'created_by' => Auth::user()->username ?? null,
            ]);
            // Delete the file from storage
            Storage::disk('public')->delete($file->file_path);
            // Delete the database record
            $file->delete();
            $this->dispatch('show-message', message: 'File deleted successfully.');
        } else {
            $this->dispatch('show-error', message: 'File not found.');
        }
    }

    public function render()
    {
        return view('livewire.view-requisition-form');
    }

    public function sendToCAB()
    {
        $this->requisitionForm->status = RequestFormStatus::SENT_TO_COST_BUDGETING;
        $this->requisitionForm->sent_to_cab = true;
        $this->requisitionForm->date_sent_to_cab = now();
        $this->requisitionForm->save();
        $costAndBudgetingUsers = User::costBudgeting()->get();
        Notification::send($costAndBudgetingUsers, new RequestForCABApproval($this->requisitionForm));
        $this->dispatch('show-message', message: 'Form sent to Cost and Budgeting for Funding Availability.');

        $this->requisitionForm->logs()->create([
            'details' => 'Form sent to Cost and Budgeting for Funding Availability by ' . Auth::user()->name,
            'created_by' => Auth::user()->username ?? null,
        ]);
    }

    public function approvedByCAB()
    {
        // sleep(2); // Simulate processing delay
        // dd($this->requisitionForm->requestingUnit->name);
        $this->requisitionForm->availability_of_funds = $this->availability_of_funds;
        $this->requisitionForm->verified_by_accounts = $this->verified_by_accounts;
        $this->requisitionForm->cab_note = $this->cab_note;
        $this->requisitionForm->votes()->sync($this->selected_votes);
        $this->requisitionForm->status = RequestFormStatus::APPROVED_BY_COST_BUDGETING;
        $this->requisitionForm->completed_by_cab = true;
        $this->requisitionForm->save();

        $this->requisitionForm->logs()->create([
            'details' => 'Requisition form approved by Cost & Budgeting user ' . Auth::user()->name . ' and sent to ' . $this->requisitionForm->requestingUnit->name,
            'created_by' => Auth::user()->username,
        ]);

        Notification::send($this->requisitionForm->contactPerson, new ApprovedByCAB($this->requisitionForm));

        return redirect()->route('queue')->with('success', 'Requisition form approved by Cost & Budgeting.');
    }
}
