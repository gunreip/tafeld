<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Personal\Table as PersonalTable;

Route::view('/', 'welcome')->name('home');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/personal', PersonalTable::class)->name('personal.index');
});

require __DIR__ . '/auth.php';
