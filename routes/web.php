<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Personal\Index as PersonalIndex;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/personal', \App\Livewire\Personal\Index::class)->name('personal.index');
});

require __DIR__ . '/auth.php';
