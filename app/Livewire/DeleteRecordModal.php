<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class DeleteRecordModal extends Component
{
    public $model;
    public $id;

    public function render()
    {
        return view('livewire.delete-record-modal');
    }
    
    #[On('show-delete-modal')]
    public function displayModal($model, $id){
        $this->model = $model;
        $this->id = $id;
        $this->dispatch('display-delete-modal');
    }

    public function deleteRecord(){
        $model = 'App\Models\\' . $this->model;
        $record = $model::find($this->id);
        $record->delete();
        $this->dispatch('show-message', message: 'Record deleted successfully');
        $this->dispatch('refresh-table');
        $this->dispatch('close-delete-modal');
    }
}
