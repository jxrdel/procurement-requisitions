<?php

namespace App\Livewire;

use App\FormCategory;
use App\Models\Department;
use App\Models\RequisitionRequestForm;
use App\Models\User;
use App\Models\Vote;
use App\RequestFormStatus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateRequisitionRequestForm extends Component
{
    use WithFileUploads;

    #[Title('Create Requsition Form')]

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

    public $availability_of_funds = false;
    public $verified_by_accounts = false;
    public $vote_no;
    public $seen_by;
    public $procurement_officer_assigned;
    public $date_received;
    public $expected_date_of_completion;

    public $units;
    public $users;

    public $items = [];
    public $validationErrors = [];
    public $votes;
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
    public $selected_votes = [];
    public $categories;


    public function render()
    {
        return view('livewire.create-requisition-request-form');
    }

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->units = Department::orderBy('name')->get();
        $this->users = User::orderBy('name')->get();
        $this->contact_person_id = Auth::user()->id;
        $this->requesting_unit = Auth::user()->department_id;
        $this->head_of_department = Auth::user()->department->head_of_department_id ?? null;
        $this->votes = Vote::orderBy('number')->get();
        $this->categories = FormCategory::options();
    }

    public function save()
    {
        // Build validation rules for items dynamically
        $rules = [
            'category' => 'required',
            'requesting_unit' => 'required|exists:departments,id',
            'head_of_department' => 'required|exists:users,id',
            'contact_person_id' => 'required|exists:users,id',
            'date' => 'required|date|date_format:Y-m-d',
            'contact_info' => 'nullable|string|max:255',
            'justification' => 'required|string',
            'location_of_delivery' => 'nullable|string|max:255',
            'date_required_by' => 'nullable|date|after_or_equal:date|date_format:Y-m-d',
            'estimated_value' => 'nullable|numeric|min:0',
            'items' => 'array|min:1',
            'uploads' => 'required|array|min:2',
            'uploads.*' => 'file|max:10240',
        ];

        try {
            $this->validate($rules, [
                'uploads.required' => 'Please upload at least 1 document.',
                'items.min' => 'Please add at least 1 item.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->validator->errors()->toArray();
            $this->dispatch('scrollToError');
            throw $e;
        }

        $form = RequisitionRequestForm::create([
            'requesting_unit' => $this->requesting_unit,
            'head_of_department_id' => $this->head_of_department,
            'contact_person_id' => $this->contact_person_id,
            'date' => $this->date,
            'category' => $this->category,
            'contact_info' => $this->contact_info,
            'location_of_delivery' => $this->location_of_delivery,
            'justification' => $this->justification,
            'date_required_by' => $this->date_required_by,
            'estimated_value' => $this->estimated_value,
            'availability_of_funds' => $this->availability_of_funds,
            'verified_by_accounts' => $this->verified_by_accounts,
            'status' => RequestFormStatus::CREATED,
            'created_by' => Auth::user()->username ?? null,
        ]);

        foreach ($this->items as $item) {
            $form->items()->create($item);
        }

        //Attach selected votes
        if (!empty($this->selected_votes)) {
            $form->votes()->attach($this->selected_votes);
        }

        if (!empty($this->uploads)) {
            foreach ($this->uploads as $upload) {
                $filename = $upload->getClientOriginalName();
                $uploadPath = $upload->store('file_uploads', 'public');
                $form->uploads()->create([
                    'file_name' => $filename,
                    'file_path' => $uploadPath,
                    'uploaded_by' => Auth::user()->name ?? null,
                ]);
            }
        }

        //Create log entry
        $form->logs()->create([
            'details' => 'Form Created by ' . (Auth::user()->name),
            'created_by' => Auth::user()->username ?? null,
        ]);

        return redirect()->route('requisition_forms.view', ['id' => $form->id])->with('success', 'Requisition form created successfully.');
    }

    public function addItem()
    {
        // Validation is performed here using the $rules property defined in the component class
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
        $this->dispatch('show-message', message: 'Item added successfully');
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

    public function updated($propertyName)
    {
        // Clear validation errors when user types
        if (str_starts_with($propertyName, 'items.')) {
            $this->validationErrors = [];
        }
    }
}
