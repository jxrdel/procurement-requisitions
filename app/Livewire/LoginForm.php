<?php

namespace App\Livewire;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;
use Livewire\Attributes\Layout;
use Livewire\Component;

class LoginForm extends Component
{
    #[Layout('components.layouts.login')]

    public $username;
    public $password;

    public function render()
    {
        return view('livewire.login-form');
    }

    public function login()
    {
        $user = User::find(1);

        Auth::login($user);
        if ($user->department === 'Procurement') {
            return redirect()->intended('/');
        } elseif ($user->department === 'Cost & Budgeting') {
            return redirect()->intended('/cost_and_budgeting');
        } elseif ($user->department === 'Vote Control') {
            return redirect()->intended('/vote_control_requisitions');
        } else {
            return redirect()->intended('/');
        }
        return redirect()->intended('/'); // Fallback to dashboard if no intended page

        // try {

        //     $connection = Container::getConnection('default');
        //     $user = User::where('username', $this->username)->first(); //Gets user

        //     if ($user) { //If user is found..
        //         $ADuser = $connection->query()->where('samaccountname', '=', $this->username)->first(); //Gets user from AD
        //         // dd($ADuser);
        //         if ($ADuser) {
        //             if ($connection->auth()->attempt($ADuser['distinguishedname'][0], $this->password)) { //Authenticate User
        //                 // dd('Success');
        //                 Auth::login($user);
        //                 redirect()->intended('/');
        //             } else {
        //                 // dd('Error');
        //                 $this->resetValidation();
        //                 $this->addError('password', 'Incorrect password');
        //                 $this->password = null;
        //             }
        //         } else {
        //             $this->resetValidation();
        //             $this->addError('username', 'User does not have a Windows Login. Please contact Administrator');
        //         }
        //     } else { //Display error if no user is found
        //         $this->resetValidation();
        //         $this->addError('username', 'User not found');
        //     }
        // } catch (Exception $e) {
        //     dd('Error: Please contact IT at ext 11124', $e->getMessage());
        // }
    }
}
