<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
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
        return redirect()->intended('/'); // Fallback to dashboard if no intended page
    }
}
