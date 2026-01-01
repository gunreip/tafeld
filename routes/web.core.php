<?php

// tafeld/routes/web.core.php
// Core-Routen (Welcome, Dashboard, Logout, Debug-Test)

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Support\RouteAudit;

use App\Livewire\Pages\Welcome;
use App\Livewire\Pages\Dashboard;

route_if_exists(Welcome::class, function () {
    Route::get('/', Welcome::class)->name('welcome');
});

Route::middleware(['auth'])->group(function () {

    route_if_exists(Dashboard::class, function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
    });
});

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->name('logout');

route_if_exists(\App\Livewire\DebugTest::class, function () {
    Route::get('/debug-test', \App\Livewire\DebugTest::class);
});
