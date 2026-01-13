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

    public function mount()
    {
        //If user is already logged in, redirect to dashboard
        if (Auth::check()) {
            redirect()->route('/');
        }
    }

    public function login()
    {
        //Donald : 25
        //Crystal CAB: 8
        //Candise Accounts Payable: 11
        //Cindy Vote Control: 24
        //Cheryl Check Staff: 12
        // Rohini Cheque Processing: 15

        // $user = User::find(43);

        // Auth::login($user);
        // return redirect()->intended('/'); // Fallback to dashboard if no intended page

        try {

            $connection = Container::getConnection('default');
            $user = User::where('username', $this->username)->first(); //Gets user

            if ($user) { //If user is found..
                $ADuser = $connection->query()->where('samaccountname', '=', $this->username)->first(); //Gets user from AD
                // dd($ADuser);
                if ($ADuser) {
                    if ($connection->auth()->attempt($ADuser['distinguishedname'][0], $this->password)) { //Authenticate User
                        if ($user->is_active == 0) {
                            $this->resetValidation();
                            $this->addError('username', 'User is inactive');
                            return;
                        }
                        Auth::login($user);
                        redirect()->intended('/');
                    } else {
                        // dd('Error');
                        $this->resetValidation();
                        $this->addError('password', 'Incorrect password');
                        $this->password = null;
                    }
                } else {
                    $this->resetValidation();
                    $this->addError('username', 'User does not have a Windows Login. Please contact ICT Helpdesk at etx. 11000');
                }
            } else { //Display error if no user is found
                $this->resetValidation();
                $this->addError('username', 'User not found');
            }
        } catch (Exception $e) {
            dd('Error: Please contact IT at ext 11124', $e->getMessage());
        }
    }
}
