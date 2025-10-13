<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\RequisitionRequestForm;
use App\Models\User;
use App\RequestFormStatus;
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

    public $units;
    public $users;

    public $items = [];
    public $item_name;
    public $qty_in_stock = 0;
    public $qty_requesting = 1;
    public $unit_of_measure;
    public $size;
    public $colour;
    public $brand_model;
    public $other;

    public $uploads;

    public $isEditing = false;

    public function mount($id)
    {
        $this->requisitionForm = RequisitionRequestForm::with('items')->findOrFail($id);

        $this->units = Department::orderBy('name')->get();
        $this->users = User::orderBy('name')->get();

        $this->requesting_unit = $this->requisitionForm->requesting_unit;
        $this->head_of_department = $this->requisitionForm->head_of_department_id;
        $this->contact_person_id = $this->requisitionForm->contact_person_id;
        $this->date = $this->requisitionForm->date ? $this->requisitionForm->date->format('Y-m-d') : null;
        $this->contact_info = $this->requisitionForm->contact_info;
        $this->location_of_delivery = $this->requisitionForm->location_of_delivery;
        $this->date_required_by = $this->requisitionForm->date_required_by ? $this->requisitionForm->date_required_by->format('Y-m-d') : null;
        $this->estimated_value = $this->requisitionForm->estimated_value;
        $this->availability_of_funds = $this->requisitionForm->availability_of_funds;
        $this->verified_by_accounts = $this->requisitionForm->verified_by_accounts;
        $this->vote_no = $this->requisitionForm->vote_no;

        $this->items = $this->requisitionForm->items->toArray();
    }

    public function save()
    {
        try {
            $this->validate([
                'requesting_unit' => 'required|exists:departments,id',
                'head_of_department' => 'required|exists:users,id',
                'contact_person_id' => 'required|exists:users,id',
                'date' => 'required|date|date_format:Y-m-d',
                'contact_info' => 'nullable|string|max:255',
                'justification' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
                'location_of_delivery' => 'nullable|string|max:255',
                'date_required_by' => 'nullable|date|after_or_equal:date|date_format:Y-m-d',
                'estimated_value' => 'nullable|numeric|min:0',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('scrollToError');
            throw $e;
        }

        if (empty($this->items)) {
            $this->dispatch('show-error', message: 'Please add at least one item to the requisition form.');
            return;
        }

        $data = [
            'requesting_unit' => $this->requesting_unit,
            'head_of_department_id' => $this->head_of_department,
            'contact_person_id' => $this->contact_person_id,
            'date' => $this->date,
            'contact_info' => $this->contact_info,
            'location_of_delivery' => $this->location_of_delivery,
            'date_required_by' => $this->date_required_by,
            'estimated_value' => $this->estimated_value,
            'availability_of_funds' => $this->availability_of_funds,
            'verified_by_accounts' => $this->verified_by_accounts,
            'vote_no' => $this->vote_no,
        ];

        $this->requisitionForm->update($data);

        if ($this->justification) {
            $justificationPath = $this->justification->store('justifications', 'public');
            $this->requisitionForm->justification_path = $justificationPath;
            $this->requisitionForm->save();
        }

        $this->requisitionForm->items()->delete();
        $this->requisitionForm->items()->createMany($this->items);

        $this->dispatch('show-message', message: 'Requisition form updated successfully.');
        $this->isEditing = false;
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
        $this->requisitionForm->status = RequestFormStatus::SENT_TO_HOD;
        $this->requisitionForm->save();

        $this->dispatch('show-message', message: 'Requisition form sent to Head of Department for approval.');
    }

    public function render()
    {
        return view('livewire.view-requisition-form');
    }
}
