<?php

// tafeld/routes/web.persons.php
// Personen-Modul

use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Persons\Index as PersonsIndex;
use App\Livewire\Pages\Persons\Create as PersonsCreate;
use App\Livewire\Pages\Persons\Edit as PersonsEdit;

Route::middleware(['auth'])
    ->prefix('persons')
    ->group(function () {

        route_if_exists(PersonsIndex::class, function () {
            Route::get('/', PersonsIndex::class)
                ->name('persons.index');
        });

        route_if_exists(PersonsCreate::class, function () {
            Route::get('/create', PersonsCreate::class)
                ->name('persons.create');
        });

        route_if_exists(PersonsEdit::class, function () {
            Route::get('/{person:id}/edit', PersonsEdit::class)
                ->name('persons.edit');
        });
    });
