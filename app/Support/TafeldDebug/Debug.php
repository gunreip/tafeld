<?php

// tafeld/app/Support/TafeldDebug/Debug.php

namespace App\Support\TafeldDebug;

use App\Support\TafeldDebug\DebugEngine;

class Debug
{
    /**
     * Liefert eine request-weit stabile run_id (ULID).
     * - HTTP: Speicherung in Request-Attributes
     * - CLI / Jobs: statischer Fallback
     */
    protected static function resolveRunId(): string
    {
        // HTTP-Request vorhanden
        if (function_exists('request') && request()) {
            if (! request()->attributes->has('_tafeld_debug_run_id')) {
                request()->attributes->set(
                    '_tafeld_debug_run_id',
                    (string) \Illuminate\Support\Str::ulid()
                );
            }

            return request()->attributes->get('_tafeld_debug_run_id');
        }

        // CLI / Fallback (einmal pro Prozess)
        static $runId = null;
        if ($runId === null) {
            $runId = (string) \Illuminate\Support\Str::ulid();
        }

        return $runId;
    }

    public static function log(
        string $scope,
        string $message,
        array $context = [],
        string $level = 'debug'
    ): void {
        app(DebugEngine::class)->handle(
            $scope,
            $level,
            $message,
            array_replace(
                ['_run_id' => self::resolveRunId()],
                $context
            )
        );
    }
}
