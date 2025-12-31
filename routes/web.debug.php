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
    use Illuminate\Support\Facades\Schema;
    use Illuminate\Support\Facades\DB;

    Route::get('/_debug_test/overview', function () {
        // Seed a small set of debug_logs if the table exists but is empty.
        if (Schema::hasTable('debug_logs') && DB::table('debug_logs')->count() === 0) {
            DB::table('debug_logs')->insert([
                [
                    'id' => (string) \Illuminate\Support\Str::ulid(),
                    'run_id' => (string) \Illuminate\Support\Str::ulid(),
                    'scope' => 'smoke.env',
                    'channel' => 'tafeld-debug',
                    'level' => 'debug',
                    'message' => 'ENV gate test (seed)',
                    'context' => json_encode(['seed' => true]),
                    'user_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'id' => (string) \Illuminate\Support\Str::ulid(),
                    'run_id' => (string) \Illuminate\Support\Str::ulid(),
                    'scope' => 'smoke.global',
                    'channel' => 'tafeld-debug',
                    'level' => 'debug',
                    'message' => 'Global enabled (seed)',
                    'context' => json_encode([]),
                    'user_id' => null,
                    'created_at' => now()->subMinutes(5),
                    'updated_at' => now()->subMinutes(5),
                ],
            ]);
        }

        // Instantiate and render the Livewire component directly so we bypass auth
        $component = app(\App\Livewire\Debug\Overview::class);
        return $component->render();
    })->name('debug.test.overview');
}
