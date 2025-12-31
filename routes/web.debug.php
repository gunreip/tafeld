<?php

// tafeld/routes/web.debug.php
// Debug UI (Livewire-only, Read-only)

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\EnsureDebugUiEnabled;

use App\Livewire\Debug\Overview;
use App\Livewire\Debug\Logs\Index as DebugLogsIndex;
use App\Livewire\Debug\Logs\Show as DebugLogsShow;
use App\Livewire\Debug\Scopes\Index as DebugScopesIndex;
use App\Livewire\Debug\Scopes\Show as DebugScopesShow;

Route::middleware(['auth', EnsureDebugUiEnabled::class])
    ->prefix('debug')
    ->name('debug.')
    ->group(function () {

        route_if_exists(
            Overview::class,
            fn() => Route::get('/', Overview::class)->name('overview')
        );

        route_if_exists(
            DebugLogsIndex::class,
            fn() => Route::get('/logs', DebugLogsIndex::class)->name('logs.index')
        );

        route_if_exists(
            DebugLogsShow::class,
            fn() => Route::get('/logs/{id}', DebugLogsShow::class)
                ->whereUlid('id')
                ->name('logs.show')
        );

        route_if_exists(
            DebugScopesIndex::class,
            fn() => Route::get('/scopes', DebugScopesIndex::class)->name('scopes.index')
        );

        route_if_exists(
            DebugScopesShow::class,
            fn() => Route::get('/scopes/{scopeKey}', DebugScopesShow::class)
                ->name('scopes.show')
        );
    });

// Test-only convenience route: exposes the Overview without auth when running tests
if (app()->environment('testing')) {
    Route::get('/_debug_test/overview', Overview::class)->name('debug.test.overview');
}
