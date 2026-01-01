<?php

// tafeld/app/Support/TafeldDebug/DebugToggleService.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class DebugToggleService
{
    /**
     * Aktiviert einen Debug-Scope (revisionssicher).
     */
    public function enableScope(string $scopeId): void
    {
        $this->toggleScope($scopeId, true);
    }

    /**
     * Deaktiviert einen Debug-Scope (revisionssicher).
     */
    public function disableScope(string $scopeId): void
    {
        $this->toggleScope($scopeId, false);
    }

    /**
     * Aktiviert Runtime-Kill für einen Debug-Scope.
     */
    public function enableRuntimeKill(string $scopeId): void
    {
        $this->toggleRuntimeKill($scopeId, true);
    }

    /**
     * Deaktiviert Runtime-Kill für einen Debug-Scope.
     */
    public function disableRuntimeKill(string $scopeId): void
    {
        $this->toggleRuntimeKill($scopeId, false);
    }

    /**
     * Aktiviert globales Debug (scope_key='*').
     */
    public function enableGlobal(): void
    {
        $this->toggleGlobal(true);
    }

    /**
     * Deaktiviert globales Debug (scope_key='*').
     */
    public function disableGlobal(): void
    {
        $this->toggleGlobal(false);
    }

    /**
     * Interne Scope-Umschaltung mit Activity-Log.
     */
    protected function toggleScope(string $scopeId, bool $enable): void
    {
        if (! Schema::hasTable('debug_scopes')) {
            return;
        }

        $scope = DB::table('debug_scopes')->where('id', $scopeId)->first();
        if (! $scope) {
            return;
        }

        $old = (bool) $scope->enabled;
        if ($old === $enable) {
            return; // keine echte Änderung
        }

        DB::table('debug_scopes')
            ->where('id', $scopeId)
            ->update([
                'enabled'    => $enable,
                'updated_at' => now(),
            ]);

        $this->logActivity(
            $enable ? 'debug_scope.enable' : 'debug_scope.disable',
            'debug_scope',
            $scopeId,
            [
                'scope_key' => $scope->scope_key,
                'old'       => $old,
                'new'       => $enable,
            ]
        );
    }

    /**
     * Interne Runtime-Kill-Umschaltung mit Activity-Log.
     */
    protected function toggleRuntimeKill(string $scopeId, bool $enable): void
    {
        if (! Schema::hasTable('debug_scopes')) {
            return;
        }

        $scope = DB::table('debug_scopes')->where('id', $scopeId)->first();
        if (! $scope) {
            return;
        }

        $old = (bool) ($scope->runtime_killable ?? false);
        if ($old === $enable) {
            return; // keine echte Änderung
        }

        DB::table('debug_scopes')
            ->where('id', $scopeId)
            ->update([
                'runtime_killable' => $enable,
                'updated_at'       => now(),
            ]);

        $this->logActivity(
            $enable
                ? 'debug_scope.runtime_kill.enable'
                : 'debug_scope.runtime_kill.disable',
            'debug_scope',
            $scopeId,
            [
                'scope_key' => $scope->scope_key,
                'old'       => $old,
                'new'       => $enable,
            ]
        );
    }

    /**
     * Interne globale Umschaltung (scope_key='*') mit Activity-Log.
     */
    protected function toggleGlobal(bool $enable): void
    {
        if (! Schema::hasTable('debug_settings')) {
            return;
        }

        $row = DB::table('debug_settings')->where('scope_key', '*')->first();
        if (! $row) {
            return;
        }

        $old = (bool) $row->enabled;
        if ($old === $enable) {
            return; // keine echte Änderung
        }

        DB::table('debug_settings')
            ->where('id', $row->id)
            ->update([
                'enabled'    => $enable,
                'updated_at' => now(),
            ]);

        $this->logActivity(
            $enable ? 'debug_global.enable' : 'debug_global.disable',
            'debug_global',
            $row->id,
            [
                'old' => $old,
                'new' => $enable,
            ]
        );
    }

    /**
     * Zentrales Schreiben ins activity_log (Spatie-kompatibel, revisionssicher).
     */
    protected function logActivity(
        string $action,
        string $subjectType,
        string $subjectId,
        array $meta
    ): void {
        if (! Schema::hasTable('activity_log')) {
            return;
        }

        $user = auth()->user();

        DB::table('activity_log')->insert([
            // 'id'           => (string) Str::ulid(),
            'log_name'     => 'tafeld-debug',
            'description'  => $action,
            'event'        => $action,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'causer_type'  => $user ? get_class($user) : null,
            'causer_id'    => $user?->id,
            'properties'   => json_encode($meta),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
