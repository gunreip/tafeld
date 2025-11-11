<?php

// Illuminate
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
// Livewire\Pages
use App\Livewire\Pages\Welcome;
use App\Livewire\Pages\Dashboard;
// Livewire\Pages\Auth
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\ResetPassword;
use App\Livewire\Pages\Auth\VerifyEmail;
use App\Livewire\Pages\Auth\ConfirmPassword;


// Welcome
Route::get('/', Welcome::class)->name('welcome');

// Livewire/Pages/Auth
// Login
Route::get('/login', Login::class)
    ->middleware('guest')
    ->name('login');

// logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Register
Route::get('/register', Register::class)
    ->middleware('guest')
    ->name('register');

// ForgetPassword
Route::get('/forgot-password', ForgotPassword::class)
    ->middleware('guest')
    ->name('password.request');

// ResetPassword
Route::get('/reset-password/{token}', ResetPassword::class)
    ->middleware('guest')
    ->name('password.reset');

// VerifyEmail
Route::get('/verify-email', VerifyEmail::class)
    ->middleware(['auth'])
    ->name('verification.notice');

// ConfirmPassword
Route::get('/confirm-password', ConfirmPassword::class)
    ->middleware('auth')
    ->name('password.confirm');

// Dashboard
Route::get('/dashboard', Dashboard::class)
    ->middleware('auth')
    ->name('dashboard');
