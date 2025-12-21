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
    public array $chartByLevel = [];

    public string $level = 'all';
    public string $period = '24h';
    public string $search = '';

    protected DebugReader $reader;

    public function mount(string $scopeKey, DebugReader $reader): void
    {
        $this->reader = $reader;
        $this->scopeKey = $scopeKey;

        // Scope laden
        $scopeData = $reader->getScope($scopeKey);
        if (!$scopeData) {
            abort(404, "Scope '{$scopeKey}' nicht gefunden.");
        }

        $this->scopeData = $scopeData;

        // Startdaten laden
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

    protected function loadLogs(): void
    {
        $from = match ($this->period) {
            '24h' => new \DateTimeImmutable('-24 hours'),
            '7d'  => new \DateTimeImmutable('-7 days'),
            '30d' => new \DateTimeImmutable('-30 days'),
            default => new \DateTimeImmutable('-1 year'),
        };

        $to = new \DateTimeImmutable('now');

        $level = $this->level === 'all' ? null : $this->level;
        $search = $this->search !== '' ? $this->search : null;

        // Logs lesen
        $collection = $this->reader->getLogs(
            scope: $this->scopeKey,
            channel: null,
            level: $level,
            from: $from,
            to: $to,
            search: $search,
            perPage: 500
        );

        $this->logs = $collection->values()->toArray();

        // Chart nach Level (global – Reader unterstützt Scope-Filter noch nicht)
        $this->chartByLevel = $this->reader->getLogsByLevel($from, $to);
    }

    public function render()
    {
        $this->dispatch('tafeld-debug-chart-refresh');

        return view('livewire.debug.scopes.show', [
            'scope'        => $this->scopeData,
            'logs'         => $this->logs,
            'chartByLevel' => $this->chartByLevel,
        ])->layout('livewire.layout.app');
    }
}
