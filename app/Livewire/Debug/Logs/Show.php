<?php

// tafeld/app/Livewire/Debug/Logs/Show.php

namespace App\Livewire\Debug\Logs;

use Livewire\Component;
use App\Models\DebugLog;
use App\Support\Breadcrumbs;

class Show extends Component
{
    public string $runId;

    public function mount(string $runId): void
    {
        $this->runId = $runId;
    }

    public function render()
    {
        $logs = DebugLog::query()
            ->where('run_id', $this->runId)
            ->orderBy('created_at')
            ->get();

        return view('livewire.debug.logs.show', [
            'logs' => $logs,
        ])->layout(
            'livewire.layout.app',
            [
                'breadcrumbs' => Breadcrumbs::for('persons.create'),
                'tafelName'   => 'Tafel Wesseling e. V.',
                'avatarUrl'   => '/images/avatars/default-user.svg',
            ]
        );
    }
}
