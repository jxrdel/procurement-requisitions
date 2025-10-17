<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\RequisitionRequestForm;
use App\Models\User;
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
    public $item_name;
    public $qty_in_stock = 0;
    public $qty_requesting = 1;
    public $unit_of_measure;
    public $size;
    public $colour;
    public $brand_model;
    public $other;

    public $uploads;


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
    }

    public function save()
    {
        // Validate the main form fields
        try {
            $this->validate([
                'requesting_unit' => 'required|exists:departments,id',
                'head_of_department' => 'required|exists:users,id',
                'contact_person_id' => 'required|exists:users,id',
                'date' => 'required|date|date_format:Y-m-d',
                'contact_info' => 'nullable|string|max:255',
                'justification' => 'required|file|mimes:pdf,doc,docx|max:5120',
                'location_of_delivery' => 'nullable|string|max:255',
                'date_required_by' => 'nullable|date|after_or_equal:date|date_format:Y-m-d',
                'estimated_value' => 'nullable|numeric|min:0',
            ], [
                'justification.required' => 'Please upload a covering memo explaining the request',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('scrollToError');
            throw $e;
        }

        if (empty($this->items)) {
            $this->dispatch('show-error', message: 'Please add at least one item to the requisition form.');
            return;
        }

        $form = RequisitionRequestForm::create([
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
            'status' => RequestFormStatus::CREATED,
            'created_by' => Auth::user()->username ?? null,
        ]);
        foreach ($this->items as $item) {
            $form->items()->create($item);
        }

        if ($this->justification) {
            $justificationPath = $this->justification->store('justifications', 'public');
            $form->justification_path = $justificationPath;
            $form->save();
        }

        if (!empty($this->uploads)) {
            foreach ($this->uploads as $upload) {
                $filename = $upload->getClientOriginalName();
                $uploadPath = $upload->store('file_uploads', 'public');
                $form->uploads()->create([
                    'file_name' => $filename,
                    'file_path' => $uploadPath,
                    'uploaded_by' => Auth::user()->username ?? null,
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
}
