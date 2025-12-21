<?php

// tafeld/app/Livewire/Debug/Overview.php

namespace App\Livewire\Debug;

use Livewire\Component;
use App\Support\TafeldDebug\DebugReader;

class Overview extends Component
{
    public array $stats = [];
    public array $chartLogsByLevel = [];
    public array $chartLogsByScope = [];
    public array $latestLogs = [];

    public function mount(DebugReader $reader): void
    {
        // Scopes
        $scopes = $reader->getScopes();

        // Statistiken
        $this->stats = [
            'total_scopes'  => count($scopes),
            'active_scopes' => count(array_filter($scopes, fn($s) => $s['enabled'])),
            'logs_24h'      => $reader->getLogCountLast24h(),
        ];

        // Diagramm-Daten
        $from = new \DateTimeImmutable('-24 hours');
        $to   = new \DateTimeImmutable('now');

        $this->chartLogsByLevel = $reader->getLogsByLevel($from, $to);
        $this->chartLogsByScope = $reader->getLogsByScope($from, $to);

        // Neuste Logs
        $this->latestLogs = $reader->getLatestLogs(15);
    }

    public function render()
    {
        return view('livewire.debug.overview', [
            'stats'               => $this->stats,
            'chartLogsByLevel'    => $this->chartLogsByLevel,
            'chartLogsByScope'    => $this->chartLogsByScope,
            'latestLogs'          => $this->latestLogs,
        ])->layout('livewire.layout.app');
    }
}
