<?php

// tafeld/app/Livewire/Debug/Scopes/Show.php

namespace App\Livewire\Debug\Scopes;

use Livewire\Component;
use App\Support\TafeldDebug\DebugReader;

class Show extends Component
{
    public string $scopeKey;
    public array $scopeData = [];

    public array $logs = [];

    public ?array $cursor = null;
    public array $pageCursors = [];
    public int $page = 1;
    public int $perPage = 20;
    public bool $hasMore = true;

    public array $chartByLevel = [];

    public string $level = 'all';
    public string $period = '24h';
    public string $search = '';

    protected DebugReader $reader;

    public function mount(string $scopeKey, DebugReader $reader): void
    {
        $this->reader = $reader;
        $this->scopeKey = $scopeKey;

        $scopeData = $reader->getScope($scopeKey);
        if (! $scopeData) {
            abort(404, "Scope '{$scopeKey}' nicht gefunden.");
        }

        $this->scopeData = $scopeData;

        $this->loadLogs();
    }

    public function updatedLevel(): void
    {
        $this->loadLogs();
    }

    public function updatedPeriod(): void
    {
        $this->loadLogs();
    }

    public function updatedSearch(): void
    {
        $this->loadLogs();
    }

    public function updatedPerPage(): void
    {
        $this->loadLogs();
    }

    protected function loadLogs(): void
    {
        $this->page = 1;
        $this->pageCursors = [];
        $this->cursor = null;
        $this->hasMore = true;

        $this->loadPage(1);
    }

    protected function loadPage(int $page): void
    {
        if ($page < 1) {
            return;
        }

        $from = match ($this->period) {
            '24h' => new \DateTimeImmutable('-24 hours'),
            '7d'  => new \DateTimeImmutable('-7 days'),
            '30d' => new \DateTimeImmutable('-30 days'),
            default => new \DateTimeImmutable('-1 year'),
        };

        $to = new \DateTimeImmutable('now');

        $level = $this->level === 'all' ? null : $this->level;
        $search = $this->search !== '' ? $this->search : null;

        $cursor = $page > 1
            ? ($this->pageCursors[$page - 1] ?? null)
            : null;

        $result = $this->reader->getLogsCursor(
            scope: $this->scopeKey,
            level: $level,
            from: $from,
            to: $to,
            search: $search,
            cursor: $cursor,
            perPage: $this->perPage
        );

        $this->logs = $result['data'];
        $this->cursor = $result['next_cursor'];
        $this->hasMore = $this->cursor !== null;

        if ($this->cursor) {
            $this->pageCursors[$page] = $this->cursor;
        }

        $this->page = $page;

        // Charts bleiben global (Scope-Aggregation im Reader derzeit nicht vorgesehen)
        $this->chartByLevel = $this->reader->getLogsByLevel($from, $to);
    }

    public function nextPage(): void
    {
        if (! $this->hasMore) {
            return;
        }

        $this->loadPage($this->page + 1);
    }

    public function prevPage(): void
    {
        if ($this->page <= 1) {
            return;
        }

        $this->loadPage($this->page - 1);
    }

    public function render()
    {
        $this->dispatch('tafeld-debug-chart-refresh');

        return view('livewire.debug.scopes.show', [
            'scope'        => $this->scopeData,
            'logs'         => $this->logs,
            'chartByLevel' => $this->chartByLevel,
            'page'         => $this->page,
            'perPage'      => $this->perPage,
            'hasMore'      => $this->hasMore,
        ])->layout('livewire.layout.app');
    }
}
