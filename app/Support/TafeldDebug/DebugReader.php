<?php

// tafeld/app/Support/TafeldDebug/DebugReader.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use DateTimeInterface;

class DebugReader
{
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function getScopes(): array
    {
        return DB::table('debug_scopes')
            ->orderBy('scope_key')
            ->get()
            ->map(fn($row) => [
                'id'               => $row->id,
                'scope_key'        => $row->scope_key,
                'enabled'          => (bool) $row->enabled,
                'runtime_killable' => (bool) ($row->runtime_killable ?? false),
                'first_seen_at'    => $row->first_seen_at ?? null,
                'last_seen_at'     => $row->last_seen_at ?? null,
            ])
            ->toArray();
    }

    public function getScope(string $scopeKey): ?array
    {
        $row = DB::table('debug_scopes')
            ->where('scope_key', $scopeKey)
            ->first();

        if (! $row) {
            return null;
        }

        return [
            'id'               => $row->id,
            'scope_key'        => $row->scope_key,
            'enabled'          => (bool) $row->enabled,
            'runtime_killable' => (bool) ($row->runtime_killable ?? false),
            'first_seen_at'    => $row->first_seen_at ?? null,
            'last_seen_at'     => $row->last_seen_at ?? null,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    public function getLogCountLast24h(): int
    {
        return DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Charts / Aggregations
    |--------------------------------------------------------------------------
    */

    public function getLogsByLevel(
        DateTimeInterface $from,
        DateTimeInterface $to
    ): array {
        return DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("properties->>'level' as level, COUNT(*) as cnt")
            ->groupBy('level')
            ->pluck('cnt', 'level')
            ->toArray();
    }

    public function getLogsByScope(
        DateTimeInterface $from,
        DateTimeInterface $to
    ): array {
        return DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->whereBetween('created_at', [$from, $to])
            ->selectRaw("properties->>'scope_key' as scope, COUNT(*) as cnt")
            ->groupBy('scope')
            ->pluck('cnt', 'scope')
            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Log Lists
    |--------------------------------------------------------------------------
    */

    /**
     * Return latest debug log entries (normalized for Blade).
     *
     * @return array<int, array<string, mixed>>
     */
    public function getLatestLogs(int $limit = 15): array
    {
        return DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn($row) => [
                'time'    => $row->created_at,
                'level'   => data_get(json_decode($row->properties, true), 'level', 'info'),
                'scope'   => data_get(json_decode($row->properties, true), 'scope_key', '—'),
                'message' => $row->description,
            ])
            ->toArray();
    }

    public function getLogs(
        ?string $scope = null,
        ?string $channel = null,
        ?string $level = null,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
        ?string $search = null,
        int $perPage = 100
    ): Collection {
        $query = DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->orderByDesc('created_at');

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        if ($scope) {
            $query->whereRaw("properties->>'scope_key' = ?", [$scope]);
        }

        if ($level) {
            $query->whereRaw("properties->>'level' = ?", [$level]);
        }

        if ($search) {
            $query->where('description', 'ILIKE', "%{$search}%");
        }

        return $query->limit($perPage)->get();
    }
}
