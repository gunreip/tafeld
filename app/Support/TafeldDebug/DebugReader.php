<?php

// tafeld/app/Support/TafeldDebug/DebugReader.php

namespace App\Support\TafeldDebug;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
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
        if (! Schema::hasTable('debug_scopes')) {
            return [];
        }

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
        if (! Schema::hasTable('debug_scopes')) {
            return null;
        }

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
        $count = 0;

        if (Schema::hasTable('activity_log')) {
            $count += DB::table('activity_log')
                ->where('log_name', 'tafeld-debug')
                ->where('created_at', '>=', now()->subDay())
                ->count();
        }

        if (Schema::hasTable('debug_logs')) {
            $count += DB::table('debug_logs')
                ->where('created_at', '>=', now()->subDay())
                ->count();
        }

        return $count;
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
        $result = [];

        if (Schema::hasTable('activity_log')) {
            $rows = DB::table('activity_log')
                ->where('log_name', 'tafeld-debug')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw("properties->>'level' as level, COUNT(*) as cnt")
                ->groupBy('level')
                ->pluck('cnt', 'level')
                ->toArray();

            foreach ($rows as $lvl => $cnt) {
                $result[$lvl] = ($result[$lvl] ?? 0) + (int) $cnt;
            }
        }

        if (Schema::hasTable('debug_logs')) {
            $rows = DB::table('debug_logs')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('level, COUNT(*) as cnt')
                ->groupBy('level')
                ->pluck('cnt', 'level')
                ->toArray();

            foreach ($rows as $lvl => $cnt) {
                $result[$lvl] = ($result[$lvl] ?? 0) + (int) $cnt;
            }
        }

        return $result;
    }

    public function getLogsByScope(
        DateTimeInterface $from,
        DateTimeInterface $to
    ): array {
        $result = [];

        if (Schema::hasTable('activity_log')) {
            $rows = DB::table('activity_log')
                ->where('log_name', 'tafeld-debug')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw("properties->>'scope_key' as scope, COUNT(*) as cnt")
                ->groupBy('scope')
                ->pluck('cnt', 'scope')
                ->toArray();

            foreach ($rows as $scope => $cnt) {
                $result[$scope] = ($result[$scope] ?? 0) + (int) $cnt;
            }
        }

        if (Schema::hasTable('debug_logs')) {
            $rows = DB::table('debug_logs')
                ->whereBetween('created_at', [$from, $to])
                ->selectRaw('scope, COUNT(*) as cnt')
                ->groupBy('scope')
                ->pluck('cnt', 'scope')
                ->toArray();

            foreach ($rows as $scope => $cnt) {
                $result[$scope] = ($result[$scope] ?? 0) + (int) $cnt;
            }
        }

        return $result;
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
        $rows = [];

        if (Schema::hasTable('activity_log')) {
            $rows = array_merge($rows, DB::table('activity_log')
                ->where('log_name', 'tafeld-debug')
                ->select(['id', 'created_at', 'description', 'properties'])
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->map(fn($r) => $this->normalizeActivityRow($r))
                ->toArray());
        }

        if (Schema::hasTable('debug_logs')) {
            $rows = array_merge($rows, DB::table('debug_logs')
                ->select(['id', 'created_at', 'message', 'scope', 'level'])
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->map(fn($r) => $this->normalizeDebugLogRow($r))
                ->toArray());
        }

        // Merge, sort by created_at desc and truncate to $limit
        usort($rows, fn($a, $b) => strtotime($b['time'] ?? '') <=> strtotime($a['time'] ?? ''));

        return array_values(array_slice($rows, 0, $limit));
    }

    private function normalizeDebugLogRow(object $row): array
    {
        $time = null;
        if (isset($row->created_at)) {
            if ($row->created_at instanceof \DateTimeInterface) {
                $time = $row->created_at->toDateTimeString();
            } elseif (is_string($row->created_at)) {
                $time = $row->created_at;
            }
        }

        return [
            'time'    => $time,
            'level'   => (string) ($row->level ?? 'info'),
            'scope'   => (string) ($row->scope ?? 'global'),
            'message' => (string) ($row->message ?? ''),
        ];
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
        if (! Schema::hasTable('activity_log')) {
            return collect();
        }

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

        return $query
            ->limit($perPage)
            ->get()
            ->map(fn($row) => $this->normalizeActivityRow($row));
    }

    /**
     * Cursor-paginated log reader (stable, deterministic).
     *
     * @param array{created_at?: string, id?: string}|null $cursor
     * @return array{data: array<int, array<string, mixed>>, next_cursor: array|null}
     */
    public function getLogsCursor(
        ?string $scope = null,
        ?string $level = null,
        ?DateTimeInterface $from = null,
        ?DateTimeInterface $to = null,
        ?string $search = null,
        ?array $cursor = null,
        int $perPage = 50
    ): array {
        if (! Schema::hasTable('activity_log')) {
            return ['data' => [], 'next_cursor' => null];
        }

        $query = DB::table('activity_log')
            ->where('log_name', 'tafeld-debug')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

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

        if ($cursor && isset($cursor['created_at'], $cursor['id'])) {
            $query->where(function ($q) use ($cursor) {
                $q->where('created_at', '<', $cursor['created_at'])
                    ->orWhere(function ($q2) use ($cursor) {
                        $q2->where('created_at', '=', $cursor['created_at'])
                            ->where('id', '<', $cursor['id']);
                    });
            });
        }

        $rows = $query->limit($perPage + 1)->get();

        $data = $rows
            ->take($perPage)
            ->map(fn($row) => $this->normalizeActivityRow($row))
            ->values()
            ->toArray();

        $nextCursor = null;
        if ($rows->count() > $perPage) {
            $last = $rows->get($perPage - 1);
            $nextCursor = [
                'created_at' => is_string($last->created_at)
                    ? $last->created_at
                    : ($last->created_at instanceof \DateTimeInterface
                        ? $last->created_at->toDateTimeString()
                        : null),
                'id' => (string) $last->id,
            ];
        }

        return [
            'data' => $data,
            'next_cursor' => $nextCursor,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Internal
    |--------------------------------------------------------------------------
    */

    private function normalizeActivityRow(object $row): array
    {
        $properties = is_string($row->properties)
            ? json_decode($row->properties, true) ?? []
            : (array) ($row->properties ?? []);

        $time = null;
        if (isset($row->created_at)) {
            if ($row->created_at instanceof \DateTimeInterface) {
                $time = $row->created_at->toDateTimeString();
            } elseif (is_string($row->created_at)) {
                $time = $row->created_at;
            }
        }

        return [
            'time'    => $time,
            'level'   => (string) ($properties['level'] ?? 'info'),
            'scope'   => (string) ($properties['scope_key'] ?? 'global'),
            'message' => (string) ($row->description ?? ''),
        ];
    }
}
