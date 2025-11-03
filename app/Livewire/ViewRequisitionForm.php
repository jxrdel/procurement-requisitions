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
use App\Notifications\RequestForHODApproval;
use App\Notifications\RequestForProcurementApproval;
use App\Notifications\RequestForReportingOfficerApproval;
use App\RequestFormStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class ViewRequisitionForm extends Component
{
    use WithFileUploads;

    public RequisitionRequestForm $requisitionForm;

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

    public $details;

    #[Computed]
    public function sendToHodDisabled()
    {
        return !$this->availability_of_funds || !$this->verified_by_accounts || empty($this->selected_votes);
    }

    //HOD Approval/Denial Modal
    public $selectedOfficer;
    public $reportingOfficers;
    public $declineReason;

    public function mount($id)
    {

        $this->requisitionForm = RequisitionRequestForm::with('items')->findOrFail($id);
        if (Gate::denies('view-requisition-form', $this->requisitionForm)) {
            abort(403, 'You do not have permission to view this requisition form.');
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
            ->orderBy('name')
            ->get();

        if ($this->requisitionForm->status === RequestFormStatus::SENT_TO_HOD) {
            $this->reportingOfficers = User::reportingOfficers()->orderBy('name')->get();
        }
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
            'estimated_value' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            // 'uploads' => 'array|min:1',
            // 'uploads.*' => 'file|max:10240',
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
            'created_by' => Auth::user()->username,
        ]);

        $this->dispatch('show-message', message: 'Form updated successfully.');
        $this->isEditing = false;
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
            // Notification::send($hod, new RequestForHODApproval($this->requisitionForm));
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
        } elseif ($reportingOfficer->reporting_officer_role == 'Deputy Permanent Secretary') {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_DPS;
        } elseif ($reportingOfficer->reporting_officer_role == 'Chief Medical Officer') {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_CMO;
        }

        $this->requisitionForm->hod_approval = true;
        $this->requisitionForm->hod_note = $this->hod_note;
        $this->requisitionForm->hod_approval_date = now();
        $this->requisitionForm->hod_digital_signature = Auth::user()->digital_signature;
        $this->requisitionForm->reporting_officer_id = $this->selectedOfficer;
        $this->requisitionForm->save();

        if ($reportingOfficer) {
            // Notification::send($reportingOfficer, new RequestForReportingOfficerApproval($this->requisitionForm));
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
        $currentUser = Auth::user();
        $logDetails = '';
        $message = '';

        $this->validate([
            'selectedOfficer' => 'required|string',
            'reporting_officer_note' => 'nullable|string',
        ]);

        // Determine which RO is approving and update the form
        if ($this->requisitionForm->reporting_officer_approval !== true) {
            if ($currentUser->id != $this->requisitionForm->reporting_officer_id) abort(403, 'You are not authorized to approve this requisition at this stage.');
            $this->requisitionForm->reporting_officer_approval = true;
            $this->requisitionForm->reporting_officer_approval_date = now();
            $this->requisitionForm->reporting_officer_digital_signature = $currentUser->digital_signature;
            $this->requisitionForm->reporting_officer_note = $this->reporting_officer_note;
        } elseif ($this->requisitionForm->second_reporting_officer_approval !== true) {
            if ($currentUser->id != $this->requisitionForm->second_reporting_officer_id) abort(403, 'You are not authorized to approve this requisition at this stage.');
            $this->requisitionForm->second_reporting_officer_approval = true;
            $this->requisitionForm->second_reporting_officer_approval_date = now();
        } elseif ($this->requisitionForm->third_reporting_officer_approval !== true) {
            if ($currentUser->id != $this->requisitionForm->third_reporting_officer_id) abort(403, 'You are not authorized to approve this requisition at this stage.');
            $this->requisitionForm->third_reporting_officer_approval = true;
            $this->requisitionForm->third_reporting_officer_approval_date = now();
        }

        // Handle forwarding
        if ($this->selectedOfficer !== 'Procurement') {


            $nextReportingOfficer = User::find($this->selectedOfficer);

            if ($this->requisitionForm->second_reporting_officer_id === null) {
                $this->requisitionForm->second_reporting_officer_id = $this->selectedOfficer;
            } elseif ($this->requisitionForm->third_reporting_officer_id === null) {
                $this->requisitionForm->third_reporting_officer_id = $this->selectedOfficer;
            }

            if ($nextReportingOfficer->reporting_officer_role == 'Permanent Secretary') {
                $this->requisitionForm->status = RequestFormStatus::SENT_TO_PS;
            } elseif ($nextReportingOfficer->reporting_officer_role == 'Deputy Permanent Secretary') {
                $this->requisitionForm->status = RequestFormStatus::SENT_TO_DPS;
            } elseif ($nextReportingOfficer->reporting_officer_role == 'Chief Medical Officer') {
                $this->requisitionForm->status = RequestFormStatus::SENT_TO_CMO;
            }

            $logDetails = 'Requisition form approved by ' . $currentUser->name . ' and sent to ' . $nextReportingOfficer->name . ' for approval.';
            $message = 'Requisition form approved and forwarded for further approval.';
            // Notification::send($nextReportingOfficer, new RequestForReportingOfficerApproval($this->requisitionForm));
        } else {
            $this->requisitionForm->status = RequestFormStatus::SENT_TO_PROCUREMENT;
            $logDetails = 'Requisition form approved by ' . $currentUser->name . ' and sent to Procurement.';
            $message = 'Requisition form approved and sent to Procurement.';

            $procurementHOD = User::where('name', 'Maryann Basdeo')->first();
            if ($procurementHOD) {
                // Notification::send($procurementHOD, new ApprovedByReportingOfficer($this->requisitionForm));
            }
        }

        $this->requisitionForm->save();

        $this->requisitionForm->logs()->create([
            'details' => $logDetails,
            'created_by' => $currentUser->username,
        ]);

        $this->reset(['selectedOfficer', 'reporting_officer_note']);
        $this->dispatch('close-ro-approval-modal');
        $this->dispatch('show-message', message: $message);
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

        // Notification::send($this->requisitionForm->headOfDepartment, new ApprovedByProcurement($this->requisitionForm));


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
            // Notification::send($this->requisitionForm->contactPerson, new DeclinedByHOD($this->requisitionForm));
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
            // Notification::send($this->requisitionForm->contactPerson, new DeclinedByReportingOfficer($this->requisitionForm));
        }

        if (Auth::user()->department->name == 'Procurement Unit') {
            $this->requisitionForm->status = RequestFormStatus::DENIED_BY_PROCUREMENT;
            $this->requisitionForm->procurement_approval = false;
            $this->requisitionForm->procurement_reason_for_denial = $this->declineReason;
            // Notification::send($this->requisitionForm->contactPerson, new DeclinedByProcurement($this->requisitionForm));
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
            // Notification::send($procurementHOD, new RequestForProcurementApproval($this->requisitionForm));
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
            'uploads.*' => 'file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ], [
            'uploads.*.mimes' => 'Each file must be a PDF, DOC, DOCX, JPG, or PNG.',
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
            }
        }

        $this->reset('uploads');

        $this->dispatch('show-message', message: 'Files uploaded successfully.');
    }

    public function deleteFile($fileId)
    {
        $file = $this->requisitionForm->uploads()->find($fileId);
        if ($file) {
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
}
