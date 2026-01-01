<?php

// tafeld/app/Support/TafeldDebug/ScopeDiscovery.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ScopeDiscovery
{
    /**
     * Stellt sicher, dass ein Scope existiert.
     * Neue Scopes werden auto-registriert (enabled = false).
     * Idempotent, wirft keine Exceptions nach außen.
     *
     * @return array{enabled: bool}|array
     */
    public function ensure(
        string $scope,
        ?string $origin = null,
        ?string $route = null,
        ?string $component = null,
        ?string $field = null
    ): array {
        if (! Schema::hasTable('debug_scopes')) {
            return ['enabled' => false];
        }

        $now = now();

        $row = DB::table('debug_scopes')
            ->where('scope_key', $scope)
            ->first();

        if ($row) {
            // last_seen aktualisieren (idempotent)
            DB::table('debug_scopes')
                ->where('id', $row->id)
                ->update(['last_seen_at' => $now]);

            return (array) $row;
        }

        // Auto-Discovery: neu anlegen (standardmäßig disabled)
        DB::table('debug_scopes')->insert([
            'id'            => (string) Str::ulid(),
            'scope_key'     => $scope,
            'enabled'       => false,
            'first_seen_at' => $now,
            'last_seen_at'  => $now,
            'origin'        => $origin ?? $this->detectOrigin(),
            'route'         => $route ?? Request::path(),
            'component'     => $component,
            'field'         => $field,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        return ['enabled' => false];
    }

    /**
     * Grobe Herkunftsbestimmung.
     */
    protected function detectOrigin(): string
    {
        return Request::route() ? 'route' : 'unknown';
    }
}
