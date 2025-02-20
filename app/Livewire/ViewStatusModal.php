<?php

namespace App\Livewire;

use App\Models\Requisition;
use Livewire\Attributes\On;
use Livewire\Component;

class ViewStatusModal extends Component
{
    public $requisition;
    public $vendors = [];

    public function render()
    {
        return view('livewire.view-status-modal');
    }

    #[On('show-status-modal')]
    public function displayModal($id)
    {
        $this->requisition = Requisition::find($id);
        $this->vendors = $this->requisition->vendors;
        $this->dispatch('display-status-modal');
    }
}
