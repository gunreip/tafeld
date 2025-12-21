<?php

// tafeld/app/Http/Middleware/AssignRunId.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AssignRunId
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Run-ID erzeugen und global im Application Container ablegen
        $runId = \Illuminate\Support\Str::ulid();
        app()->instance('tafeld.run_id', (string) $runId);

        return $next($request);
    }
}
