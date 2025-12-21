<?php

// tafeld/routes/web.php

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

// Livewire-Pages (Debug)
use App\Livewire\Pages\Dashboard;
use App\Livewire\Debug\Overview;
use App\Livewire\Debug\Scopes\Index as DebugScopesIndex;
use App\Livewire\Debug\Scopes\Show as DebugScopesShow;
use App\Livewire\Debug\Logs\Index as DebugLogsIndex;
use App\Livewire\Debug\Logs\Show as DebugLogsShow;

// Middleware
use App\Http\Middleware\EnsureDebugUiEnabled;

// Livewire-Pages (Persons)
use App\Livewire\Pages\Persons\Index as PersonsIndex;
use App\Livewire\Pages\Persons\Create as PersonsCreate;
use App\Livewire\Pages\Persons\Edit as PersonsEdit;

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
| Authentifizierte Bereiche
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
    | Persons
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

    /*
    |--------------------------------------------------------------------------
    | Debug (Livewire-only, Read-only)
    |--------------------------------------------------------------------------
    */
    Route::prefix('debug')
        ->name('debug.')
        ->middleware(EnsureDebugUiEnabled::class)
        ->group(function () {

            // Übersicht
            Route::get('/', Overview::class)
                ->name('overview');

            // Logs
            if (class_exists(DebugLogsIndex::class)) {
                Route::get('/logs', DebugLogsIndex::class)
                    ->name('logs.index');
            }

            if (class_exists(DebugLogsShow::class)) {
                Route::get('/logs/{id}', DebugLogsShow::class)
                    ->whereUlid('id')
                    ->name('logs.show');
            }

            // Scopes
            if (class_exists(DebugScopesIndex::class)) {
                Route::get('/scopes', DebugScopesIndex::class)
                    ->name('scopes.index');
            }

            if (class_exists(DebugScopesShow::class)) {
                Route::get('/scopes/{scopeKey}', DebugScopesShow::class)
                    ->name('scopes.show');
            }
        });
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Debug-Test
|--------------------------------------------------------------------------
*/

Route::get('/debug-test', \App\Livewire\DebugTest::class);
