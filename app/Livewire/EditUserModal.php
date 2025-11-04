<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Livewire\Attributes\On;
use Livewire\Component;

class EditUserModal extends Component
{
    public $user;
    public $name;
    public $username;
    public $email;
    public $department;
    public $roles;
    public $is_active;
    public $role_id;
    public $departments;

    public function render()
    {
        $this->departments = Department::orderBy('name')->get();
        $this->roles = Role::all();
        return view('livewire.edit-user-modal');
    }

    #[On('show-edit-modal')]
    public function displayModal($id)
    {
        $this->user = User::find($id);
        $this->name = $this->user->name;
        $this->username = $this->user->username;
        $this->email = $this->user->email;
        $this->department = $this->user->department->id;
        $this->is_active = $this->user->is_active == 1 ? true : false;
        $this->role_id = $this->user->role_id;
        $this->dispatch('display-edit-modal');
    }

    public function editUser()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $this->user->id,
            'email' => 'required|email|unique:users,email,' . $this->user->id,
            'department' => 'required',
            'role_id' => 'required'
        ]);

        $this->user->update([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'department_id' => $this->department,
            'is_active' => $this->is_active,
            'role_id' => $this->role_id
        ]);

        $this->dispatch('close-edit-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'User edited successfully');
    }
}
