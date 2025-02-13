<?php

namespace App\Livewire;

use App\Models\Vote;
use Livewire\Attributes\On;
use Livewire\Component;

class EditVoteModal extends Component
{
    public $vote;
    public $name;
    public $number;
    public $is_active = true;

    public function render()
    {
        return view('livewire.edit-vote-modal');
    }

    #[On('show-edit-modal')]
    public function displayModal($id)
    {
        $this->vote = Vote::find($id);
        $this->name = $this->vote->name;
        $this->number = $this->vote->number;
        $this->is_active = $this->vote->is_active;
        $this->dispatch('display-edit-modal');
    }

    public function editVote()
    {
        $this->validate(
            [
                'name' => 'required',
                'number' => 'required|unique:votes,number,' . $this->vote->id,
            ],
            [
                'number.unique' => 'Theis vote already exists.',
            ]
        );

        $this->vote->update([
            'name' => $this->name,
            'number' => $this->number,
            'is_active' => $this->is_active,
        ]);

        $this->dispatch('close-edit-modal');
        $this->dispatch('show-message', message: 'Vote edited successfully');
        $this->dispatch('refresh-table');
    }
}
