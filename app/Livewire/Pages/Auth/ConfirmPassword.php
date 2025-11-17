<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ConfirmPassword extends Component
{
    public string $password = '';
    public string $errorMessage = '';

    public function render()
    {
        return view('livewire.pages.auth.confirm-password')
            ->layout('livewire.layout.guest');
    }

    public function confirm()
    {
        $this->validate([
            'password' => ['required'],
        ]);

        if (! Hash::check($this->password, Auth::user()->password)) {
            $this->errorMessage = 'Das Passwort ist nicht korrekt.';
            return;
        }

        session()->password_confirmed_at = time();

        return redirect()->intended('/dashboard');
    }
}
