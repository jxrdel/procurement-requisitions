<?php

namespace App\Livewire;

use App\Mail\NewUserEmail;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class CreateUserModal extends Component
{
    public $name;
    public $username;
    public $email;
    public $department;
    public $role_id;
    public $roles;
    public $sendEmail = true;
    public $departments;
    public $is_reporting_officer = false;
    public $reporting_officer_role;

    public function render()
    {
        $this->roles = Role::all();
        $this->departments = Department::orderBy('name')->get();
        return view('livewire.create-user-modal');
    }

    public function createUser()
    {
        $this->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'department' => 'required|exists:departments,id',
            'role_id' => 'required',
            'reporting_officer_role' => 'required_if:is_reporting_officer,true'
        ]);

        $newuser = User::create([
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'department_id' => $this->department,
            'role_id' => $this->role_id,
            'is_reporting_officer' => $this->is_reporting_officer,
            'reporting_officer_role' => $this->is_reporting_officer ? $this->reporting_officer_role : null
        ]);

        if ($this->sendEmail) {
            Mail::to($this->email)->send(new NewUserEmail($newuser));
        }

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
