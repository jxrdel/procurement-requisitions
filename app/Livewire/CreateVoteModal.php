<?php

namespace App\Livewire;

use App\Models\Vote;
use Livewire\Component;

class CreateVoteModal extends Component
{
    public $name;
    public $number;
    public $is_active = true;

    public function render()
    {
        return view('livewire.create-vote-modal');
    }

    public function createVote()
    {
        $this->validate([
            'name' => 'required',
            'number' => 'required|unique:votes',
        ]);

        Vote::create([
            'name' => $this->name,
            'number' => $this->number,
            'is_active' => $this->is_active,
        ]);

        $this->reset();
        $this->dispatch('close-create-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'Vote created successfully');
    }
}
