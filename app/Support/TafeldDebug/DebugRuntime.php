<?php

// tafeld/app/Support/TafeldDebug/DebugRuntime.php

namespace App\Support\TafeldDebug;

class DebugRuntime
{
    /**
     * Interner Runtime-State (request-lokal, RAM-only).
     */
    protected static bool $killed = false;

    protected static ?string $reason = null;

    protected static ?string $scope = null;

    /**
     * Aktiviert den Runtime-Kill.
     * Idempotent: mehrfacher Aufruf überschreibt nichts.
     */
    public static function kill(
        string $reason,
        ?string $scope = null
    ): void {
        if (self::$killed === true) {
            return;
        }

        self::$killed = true;
        self::$reason = $reason;
        self::$scope  = $scope;
    }

    /**
     * Prüft, ob der Runtime-Kill aktiv ist.
     */
    public static function isKilled(): bool
    {
        return self::$killed;
    }

    /**
     * Liefert Statusinformationen (read-only).
     */
    public static function status(): array
    {
        return [
            'killed' => self::$killed,
            'reason' => self::$reason,
            'scope'  => self::$scope,
        ];
    }

    /**
     * Reset (nur für Tests).
     * Nicht für Produktivpfade gedacht.
     */
    public static function reset(): void
    {
        self::$killed = false;
        self::$reason = null;
        self::$scope  = null;
    }
}
