<?php

// tafeld/app/Http/Middleware/EnsureDebugUiEnabled.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDebugUiEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        // Livewire-Requests dürfen nicht durch das Debug-UI-Gate blockiert werden
        if ($request->routeIs('livewire.*')) {
            return $next($request);
        }

        // ENV-Gate: Debug-UI explizit erlauben
        if (! (bool) env('TAFELD_DEBUG_UI_ENABLED', false)) {
            abort(404);
        }

        // Optionaler Role/Gate-Check (nur wenn vorhanden)
        $user = $request->user();
        if ($user && method_exists($user, 'hasRole')) {
            if (! $user->hasRole(['admin', 'developer'])) {
                abort(403);
            }
        }

        return $next($request);
    }
}
