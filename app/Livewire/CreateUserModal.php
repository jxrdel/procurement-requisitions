<?php

namespace App\Livewire;

use App\Models\Role;
use App\Models\User;
use Livewire\Component;

class CreateUserModal extends Component
{
    public $name;
    public $username;
    public $email;
    public $department;
    public $role_id;
    public $roles;

    public function render()
    {
        $this->roles = Role::all();
        return view('livewire.create-user-modal');
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'department' => 'required',
            'role_id' => 'required'
        ]);

        User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'department' => $this->department,
            'role_id' => $this->role_id
        ]);

        $this->reset();
        $this->dispatch('close-create-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'User created successfully');
    }

    public function updatedName()
    {
        $this->username = strtolower(str_replace(' ', '.', $this->name));
        $this->email = $this->username . '@health.gov.tt';
    }
}
