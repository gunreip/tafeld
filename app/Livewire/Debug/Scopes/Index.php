<?php

// tafeld/app/Livewire/Debug/Scopes/Index.php

namespace App\Livewire\Debug\Scopes;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Support\TafeldDebug\DebugToggleService;
use App\Support\Breadcrumbs;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    /**
     * Livewire-State: enabled/runtime_killable je Scope-ID
     */
    public array $enabled = [];
    public array $runtimeKill = [];

    /**
     * Initialisiert den State aus der Datenbank.
     */
    public function mount(): void
    {
        $scopes = DB::table('debug_scopes')
            ->select('id', 'enabled', 'runtime_killable')
            ->get();

        foreach ($scopes as $scope) {
            $this->enabled[$scope->id] = (bool) $scope->enabled;
            $this->runtimeKill[$scope->id] = (bool) ($scope->runtime_killable ?? false);
        }
    }

    /**
     * Lädt alle Debug-Scopes für die Übersicht.
     */
    public function getScopesProperty()
    {
        return DB::table('debug_scopes')
            ->orderBy('scope_key')
            ->get();
    }

    /**
     * Reagiert ausschließlich auf User-Änderungen von "enabled".
     */
    public function updatedEnabled($value, string $scopeId): void
    {
        Log::channel('tafeld-debug')->debug('Debug scope toggled', [
            'scope_id' => $scopeId,
            'enabled'  => $value,
        ]);

        $service = app(DebugToggleService::class);

        $value
            ? $service->enableScope($scopeId)
            : $service->disableScope($scopeId);

        $this->reloadStateForScope($scopeId);
    }

    /**
     * Reagiert ausschließlich auf User-Änderungen von "runtimeKill".
     */
    public function updatedRuntimeKill($value, string $scopeId): void
    {
        Log::channel('tafeld-debug')->debug('Debug scope toggled', [
            'scope_id' => $scopeId,
            'enabled'  => $value,
        ]);

        $service = app(DebugToggleService::class);

        $value
            ? $service->enableRuntimeKill($scopeId)
            : $service->disableRuntimeKill($scopeId);

        $this->reloadStateForScope($scopeId);
    }

    /**
     * Synchronisiert den Livewire-State gezielt aus der Datenbank
     * (Quelle der Wahrheit = DB).
     */
    protected function reloadStateForScope(string $scopeId): void
    {
        $scope = DB::table('debug_scopes')
            ->select('enabled', 'runtime_killable')
            ->where('id', $scopeId)
            ->first();

        if (! $scope) {
            return;
        }

        $this->enabled[$scopeId] = (bool) $scope->enabled;
        $this->runtimeKill[$scopeId] = (bool) ($scope->runtime_killable ?? false);
    }

    public function render()
    {
        Log::channel('tafeld-debug')->debug('Debug-Index render()', [
            'context' => 'render()',
        ]);

        return view(
            'livewire.debug.scopes.index',
            []
        )->layout(
            'livewire.layout.app',
            [
                'breadcrumbs' => Breadcrumbs::for('persons.create'),
                'tafelName'   => 'Tafel Wesseling e. V.',
                'avatarUrl'   => '/images/avatars/default-user.svg',
            ]
        );
    }
}
