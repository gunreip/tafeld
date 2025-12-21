<?php

// tafeld/app/Support/TafeldDebug/FatalFallbackLogger.php

namespace App\Support\TafeldDebug;

class FatalFallbackLogger
{
    /**
     * Registriert einen Shutdown-Handler zur Erfassung von Fatals / OOM.
     * Extrem defensiv: keine Framework-Abhängigkeiten, kein Logger.
     */
    public static function register(): void
    {
        register_shutdown_function(function () {
            $error = error_get_last();
            if (! is_array($error)) {
                return;
            }

            // Nur echte Fatals / OOM erfassen
            if (! in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE], true)) {
                return;
            }

            // Ziel-Datei (dediziert, minimal)
            $file = self::logFile();

            // Minimaler Payload — keine großen Daten, kein JSON
            $line = sprintf(
                "[%s] FATAL: %s | %s:%s\n",
                date('Y-m-d H:i:s'),
                self::truncate($error['message'] ?? 'unknown', 500),
                $error['file'] ?? 'unknown',
                $error['line'] ?? '0'
            );

            // Best-effort write, niemals Exceptions werfen
            @file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
        });
    }

    /**
     * Liefert den Pfad zur Fallback-Logdatei.
     */
    protected static function logFile(): string
    {
        // Keine Helper wie storage_path() verwenden (könnten selbst Fehler werfen)
        return dirname(__DIR__, 3) . '/storage/logs/debug-fatal.log';
    }

    /**
     * Kürzt Strings defensiv.
     */
    protected static function truncate(string $value, int $max): string
    {
        if (strlen($value) <= $max) {
            return $value;
        }

        return substr($value, 0, $max) . '…';
    }
}
