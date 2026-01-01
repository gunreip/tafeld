<?php

// tafeld/routes/web.php

/*
|--------------------------------------------------------------------------
| Web Routes – Bootstrap
|--------------------------------------------------------------------------
|
| Diese Datei enthält bewusst KEINE Routen.
| Alle Routen sind thematisch in einzelne Dateien ausgelagert.
|
*/

require __DIR__ . '/web.auth.php';
require __DIR__ . '/web.core.php';
require __DIR__ . '/web.persons.php';
require __DIR__ . '/web.debug.php';
require __DIR__ . '/web.admin.php';

/*
|--------------------------------------------------------------------------
| Route-Audit Test (temporär)
|--------------------------------------------------------------------------
|
| Diese Route dient ausschließlich dazu, den RouteAudit-Mechanismus
| zu testen. Sie MUSS einen Warning-Eintrag erzeugen.
|
*/

// if (class_exists(\App\Livewire\__RouteAuditTest__::class)) {
//     Route::get('/__route-audit-test__', \App\Livewire\__RouteAuditTest__::class);
// } else {
//     Log::warning('Route skipped: missing class ' . \App\Livewire\__RouteAuditTest__::class);
//     RouteAudit::missing(\App\Livewire\__RouteAuditTest__::class);
// }
