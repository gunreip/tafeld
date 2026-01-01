<?php

// tafeld/app/helpers/routes.php
// Helper für routes/*.php – zentrale Route-Audit-Logik

use Illuminate\Support\Facades\Log;
use App\Support\RouteAudit;

/**
 * Definiert eine Route nur, wenn die zugehörige Klasse existiert.
 * Andernfalls wird ein Warning + RouteAudit-Eintrag erzeugt.
 *
 * @param  class-string  $class
 * @param  Closure       $defineRoute
 */
function route_if_exists(string $class, Closure $defineRoute): void
{
    if (class_exists($class)) {
        $defineRoute();
        return;
    }

    Log::warning('Route skipped: missing class ' . $class);
    RouteAudit::missing($class);
}
