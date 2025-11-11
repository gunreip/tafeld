<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function render()
    {
        return view('livewire.pages.auth.login')
            ->layout('layouts.guest');
    }

    public function login()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        $this->addError('email', 'Die Zugangsdaten sind ungültig.');
    }
}
