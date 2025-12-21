<?php

// tafeld/app/Support/TafeldDebug/DebugEngine.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use App\Support\TafeldDebug\ScopeDiscovery;
use App\Support\TafeldDebug\DbLogWriter;
use App\Support\TafeldDebug\DebugRuntime;

class DebugEngine
{
    /**
     * Zentrale Einstiegsmethode des Debug Core v1.
     * Gibt keinerlei Exceptions nach außen.
     */
    public function handle(
        string $scope,
        string $level,
        string $message,
        array $context = []
    ): void {

        // ------------------------------------------------------------
        // Re-Entrancy-Guard: Debug darf sich nicht selbst erneut auslösen
        // ------------------------------------------------------------
        if ($this->isDebugging()) {
            return;
        }

        $this->markDebugging(true);

        try {
            // ------------------------------------------------------------
            // Guard: Debug-UI niemals selbst debuggen
            // ------------------------------------------------------------
            $path = Request::path();
            if (is_string($path) && str_starts_with($path, 'debug')) {
                return;
            }

            // ------------------------------------------------------------
            // Scope-Blacklist
            // ------------------------------------------------------------
            if ($this->isBlacklistedScope($scope)) {
                return;
            }

            // ------------------------------------------------------------
            // SMOKE-TEST (Variante A)
            // ------------------------------------------------------------
            if ($scope === 'smoke.kill') {
                DebugRuntime::kill('smoke-test', 'smoke.kill');
                return;
            }

            // ------------------------------------------------------------
            // Ebene 0: ENV Hard-Kill
            // ------------------------------------------------------------
            if (! $this->envEnabled()) {
                return;
            }

            // ------------------------------------------------------------
            // Ebene 1: Globales DB-Setting
            // ------------------------------------------------------------
            if (! $this->globalEnabled()) {
                return;
            }

            // ------------------------------------------------------------
            // Scope sicherstellen (Auto-Discovery)
            // ------------------------------------------------------------
            $scopeRow = app(ScopeDiscovery::class)->ensure(
                $scope,
                null,
                Request::path()
            );

            // ------------------------------------------------------------
            // Runtime-Kill (scope-sensitiv)
            // ------------------------------------------------------------
            if (
                DebugRuntime::isKilled()
                && array_key_exists('runtime_killable', $scopeRow)
                && $scopeRow['runtime_killable'] === true
            ) {
                return;
            }

            // ------------------------------------------------------------
            // Ebene 2: Explicit-Scopes-Only
            // ------------------------------------------------------------
            if (
                $scope !== 'smoke.kill'
                && (! array_key_exists('enabled', $scopeRow) || $scopeRow['enabled'] !== true)
            ) {
                return;
            }

            // ------------------------------------------------------------
            // Persistieren (DB-first)
            // ------------------------------------------------------------
            app(DbLogWriter::class)->write($scope, $level, $message, $context);
        } finally {
            // Guard immer zurücksetzen
            $this->markDebugging(false);
        }
    }

    /**
     * ENV-Hard-Switch
     */
    protected function envEnabled(): bool
    {
        return (bool) env('TAFELD_DEBUG_ENABLED', false);
    }

    /**
     * Globales Debug-Setting aus DB (scope_key='*').
     */
    protected function globalEnabled(): bool
    {
        if (! Schema::hasTable('debug_settings')) {
            return false;
        }

        $row = \Illuminate\Support\Facades\DB::table('debug_settings')
            ->where('scope_key', '*')
            ->first();

        return (bool) ($row->enabled ?? false);
    }

    /**
     * Prüft, ob Debug im aktuellen Request bereits aktiv ist.
     */
    protected function isDebugging(): bool
    {
        if (function_exists('request') && request()) {
            return (bool) request()->attributes->get('_tafeld_debug_active', false);
        }

        // CLI / Fallback
        static $active = false;
        return $active;
    }

    /**
     * Setzt oder entfernt das Debug-Aktiv-Flag.
     */
    protected function markDebugging(bool $state): void
    {
        if (function_exists('request') && request()) {
            request()->attributes->set('_tafeld_debug_active', $state);
            return;
        }

        // CLI / Fallback
        static $active = false;
        $active = $state;
    }

    /**
     * Prüft, ob ein Scope auf der Blacklist steht.
     */
    protected function isBlacklistedScope(string $scope): bool
    {
        foreach (['debug.', 'ui.', 'livewire.'] as $prefix) {
            if (str_starts_with($scope, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
