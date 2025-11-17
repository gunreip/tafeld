<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Support\RouteAudit;

// Livewire-Pages (Auth)
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\ResetPassword;
use App\Livewire\Pages\Auth\VerifyEmail;
use App\Livewire\Pages\Auth\ConfirmPassword;

// Livewire-Pages (Core)
use App\Livewire\Pages\Welcome;
use App\Livewire\Pages\Dashboard;

// Livewire-Pages (Persons)
use App\Livewire\Pages\Persons\Index as PersonsIndex;
use App\Livewire\Pages\Persons\Create as PersonsCreate;
use App\Livewire\Pages\Persons\Edit as PersonsEdit;

// Models
use App\Models\Person;

/*
|--------------------------------------------------------------------------
| Öffentliche Routen (ohne Auth)
|--------------------------------------------------------------------------
*/

Route::get('/', Welcome::class)->name('welcome');

if (class_exists(Login::class)) {
    Route::get('/login', Login::class)->name('login');
} else {
    Log::warning('Route skipped: missing class ' . Login::class);
    RouteAudit::missing(Login::class);
}

if (class_exists(Register::class)) {
    Route::get('/register', Register::class)->name('register');
} else {
    Log::warning('Route skipped: missing class ' . Register::class);
    RouteAudit::missing(Register::class);
}

if (class_exists(ForgotPassword::class)) {
    Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
} else {
    Log::warning('Route skipped: missing class ' . ForgotPassword::class);
    RouteAudit::missing(ForgotPassword::class);
}

if (class_exists(ResetPassword::class)) {
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
} else {
    Log::warning('Route skipped: missing class ' . ResetPassword::class);
    RouteAudit::missing(ResetPassword::class);
}

if (class_exists(VerifyEmail::class)) {
    Route::get('/verify-email', VerifyEmail::class)->name('verification.notice');
} else {
    Log::warning('Route skipped: missing class ' . VerifyEmail::class);
    RouteAudit::missing(VerifyEmail::class);
}

if (class_exists(ConfirmPassword::class)) {
    Route::get('/confirm-password', ConfirmPassword::class)->name('password.confirm');
} else {
    Log::warning('Route skipped: missing class ' . ConfirmPassword::class);
    RouteAudit::missing(ConfirmPassword::class);
}

/*
|--------------------------------------------------------------------------
| Authentifizierte Bereiche (auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard
    if (class_exists(Dashboard::class)) {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    } else {
        Log::warning('Route skipped: missing class ' . Dashboard::class);
        RouteAudit::missing(Dashboard::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Persons-Modul
    |--------------------------------------------------------------------------
    */
    Route::prefix('persons')->group(function () {

        if (class_exists(PersonsIndex::class)) {
            Route::get('/', PersonsIndex::class)->name('persons.index');
        } else {
            Log::warning('Route skipped: missing class ' . PersonsIndex::class);
            RouteAudit::missing(PersonsIndex::class);
        }

        if (class_exists(PersonsCreate::class)) {
            Route::get('/create', PersonsCreate::class)->name('persons.create');
        } else {
            Log::warning('Route skipped: missing class ' . PersonsCreate::class);
            RouteAudit::missing(PersonsCreate::class);
        }

        if (class_exists(PersonsEdit::class)) {
            Route::get('/{person:id}/edit', PersonsEdit::class)->name('persons.edit');
        } else {
            Log::warning('Route skipped: missing class ' . PersonsEdit::class);
            RouteAudit::missing(PersonsEdit::class);
        }
    });
});

/*
|--------------------------------------------------------------------------
| Logout (POST)
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
