<?php

// tafeld/routes/web.admin.php
// Admin UI (Livewire-only, Gate-geschützt)

use Illuminate\Support\Facades\Route;

use App\Livewire\Admin\AppSettings\Index as AdminAppSettingsIndex;
use App\Livewire\Admin\ActivityLog\Index as AdminActivityLogIndex;

Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        route_if_exists(
            AdminAppSettingsIndex::class,
            fn() => Route::get('/app-settings', AdminAppSettingsIndex::class)
                ->name('app-settings.index')
        );

        route_if_exists(
            AdminActivityLogIndex::class,
            fn() => Route::get('/activity-log', AdminActivityLogIndex::class)
                ->name('activity-log.index')
        );
    });
