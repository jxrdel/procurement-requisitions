<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Requisition;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class ViewRequisition extends Component
{
    
    #[Title('View Requsition')]

    public $record;
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

    public $logdetails;

    public $departments;
    public $staff;
    public $logs;


    public function render()
    {
        // $this->logs = $this->requisition->statuslogs;
        $this->departments = Department::all();
        $this->staff = User::procurement()->get();
        return view('livewire.view-requisition');
    }

    // public function mount($id)
    // {

    //     $this->record = Requisition::find($id);
    //     $this->requisition_status = $this->record->requisition_status;
    //     $this->requisition_no = $this->record->requisition_no;
    //     $this->requesting_unit = $this->record->requesting_unit;
    //     $this->file_number = $this->record->file_number;
    //     $this->item = $this->record->item;
    //     $this->source_of_funds = $this->record->source_of_funds;
    //     $this->assigned_to = $this->record->assigned_to;
    //     $this->date_sent_ps = $this->record->date_sent_ps;
    //     $this->ps_approval = $this->record->ps_approval;
    //     $this->ps_approval_date = $this->record->ps_approval_date;
    //     $this->sent_to_dfa = $this->record->sent_to_dfa;
    //     $this->date_sent_dfa = $this->record->date_sent_dfa;
        

    // }
}
