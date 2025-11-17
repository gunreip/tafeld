<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public string $status = '';

    public function mount()
    {
        $this->status = session('status');
    }

    public function render()
    {
        return view('livewire.pages.auth.verify-email')
            ->layout('livewire.layout.guest');
    }

    public function resendVerification()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }

        Auth::user()->sendEmailVerificationNotification();

        $this->status = __('verification-link-sent');
        session()->flash('status', $this->status);
    }
}
