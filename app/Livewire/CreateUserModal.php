<?php

namespace App\Livewire;

use App\Mail\NewUserEmail;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use LdapRecord\Models\ActiveDirectory\User as ActiveDirectoryUser;
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
    public $ldapUser;

    public function render()
    {
        $this->roles = Role::all();
        $this->departments = Department::orderBy('name')->get();
        $ldapUsers = [];

        ActiveDirectoryUser::select(['givenname', 'sn', 'samaccountname'])
            ->orderBy('givenname')
            ->chunk(1000, function ($chunk) use (&$ldapUsers) {
                foreach ($chunk as $user) {
                    $ldapUsers[] = $user;
                }
            });

        return view('livewire.create-user-modal', compact(
            'ldapUsers'
        ));
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
            Notification::send($newuser, new \App\Notifications\UserCreatedNotification($newuser));
            Log::info('Run in tinker if fails: $user = User::find(' . $newuser->id . ');');
            Log::info('Run in tinker if fails: \Illuminate\Support\Facades\Notification::send($user, new \App\Notifications\UserCreatedNotification($user));');
        }

        $this->reset();
        $this->dispatch('close-create-modal');
        $this->dispatch('refresh-table');
        $this->dispatch('show-message', message: 'User created successfully');
    }
    public function selectAdUser($samaccountname)
    {
        // Find the specific user from AD
        $user = ActiveDirectoryUser::where('samaccountname', $samaccountname)->first();

        if ($user) {
            $firstName = trim($user->getFirstAttribute('givenname'));
            $lastName = trim($user->getFirstAttribute('sn'));
            $this->name = "$firstName $lastName";

            $this->username = strtolower($user->getFirstAttribute('samaccountname'));

            $this->email = $this->username . '@health.gov.tt';
        }
    }

    public function updatedName()
    {
        if (empty($this->username)) {
            $this->username = strtolower(str_replace(' ', '.', $this->name));
            $this->email = $this->username . '@health.gov.tt';
        }
    }
}
