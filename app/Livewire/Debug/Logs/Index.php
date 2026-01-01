<?php

// tafeld/app/Livewire/Debug/Logs/Index.php

namespace App\Livewire\Debug\Logs;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Support\Breadcrumbs;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // -------------------------------------------------
    // Filter
    // -------------------------------------------------
    public string $scope = '';
    public string $level = '';
    public string $run_id = '';
    public ?string $from = null;
    public ?string $to = null;

    // -------------------------------------------------
    // Suggestions
    // -------------------------------------------------
    public array $scopeSuggestions = [];
    public array $runIdSuggestions = [];

    // -------------------------------------------------
    // Pagination
    // -------------------------------------------------
    public int $perPage = 50;

    // -------------------------------------------------
    // Lifecycle
    // -------------------------------------------------
    public function updating($name): void
    {
        if (in_array($name, [
            'scope',
            'level',
            'run_id',
            'from',
            'to',
            'perPage',
        ], true)) {
            $this->resetPage();
        }
    }

    // -------------------------------------------------
    // Date Helpers (dd-mm-yyyy -> Carbon range)
    // -------------------------------------------------
    protected function parseFilterDate(?string $value): ?Carbon
    {
        if (!$value) {
            return null;
        }

        return Carbon::createFromFormat('d-m-Y', $value);
    }

    protected function fromDateTime(): ?Carbon
    {
        $dt = $this->parseFilterDate($this->from);
        return $dt?->startOfDay();
    }

    protected function toDateTime(): ?Carbon
    {
        $dt = $this->parseFilterDate($this->to);
        return $dt?->endOfDay();
    }

    // -------------------------------------------------
    // Main Query
    // -------------------------------------------------
    protected function query()
    {
        $q = DB::table('debug_logs')
            ->select([
                'id',
                'run_id',
                'scope',
                'level',
                'message',
                'created_at',
            ])
            ->orderByDesc('created_at');

        if ($this->scope !== '') {
            $q->where('scope', 'like', '%' . $this->scope . '%');
        }

        if ($this->level !== '') {
            $q->where('level', $this->level);
        }

        if ($this->run_id !== '') {
            $q->where('run_id', $this->run_id);
        }

        if ($this->from) {
            $from = $this->fromDateTime();
            if ($from) {
                $q->where('created_at', '>=', $from);
            }
        }

        if ($this->to) {
            $to = $this->toDateTime();
            if ($to) {
                $q->where('created_at', '<=', $to);
            }
        }

        return $q;
    }

    // -------------------------------------------------
    // Scope Suggestions
    // -------------------------------------------------
    protected function buildScopeSuggestions(): array
    {
        $q = DB::table('debug_logs')
            ->select('scope')
            ->distinct();

        if ($this->level !== '') {
            $q->where('level', $this->level);
        }

        if ($this->run_id !== '') {
            $q->where('run_id', $this->run_id);
        }

        if ($this->from) {
            $from = $this->fromDateTime();
            if ($from) {
                $q->where('created_at', '>=', $from);
            }
        }

        if ($this->to) {
            $to = $this->toDateTime();
            if ($to) {
                $q->where('created_at', '<=', $to);
            }
        }

        if ($this->scope !== '') {
            $q->where('scope', 'like', '%' . $this->scope . '%');
        }

        return $q
            ->orderBy('scope')
            ->limit(20)
            ->pluck('scope')
            ->values()
            ->all();
    }

    // -------------------------------------------------
    // Run-ID Suggestions
    // -------------------------------------------------
    protected function buildRunIdSuggestions(): array
    {
        $q = DB::table('debug_logs')
            ->select('run_id')
            ->whereNotNull('run_id')
            ->distinct();

        if ($this->level !== '') {
            $q->where('level', $this->level);
        }

        if ($this->scope !== '') {
            $q->where('scope', 'like', '%' . $this->scope . '%');
        }

        if ($this->from) {
            $from = $this->fromDateTime();
            if ($from) {
                $q->where('created_at', '>=', $from);
            }
        }

        if ($this->to) {
            $to = $this->toDateTime();
            if ($to) {
                $q->where('created_at', '<=', $to);
            }
        }

        if ($this->run_id !== '') {
            $q->where('run_id', 'like', '%' . $this->run_id . '%');
        }

        return $q
            ->orderByDesc('run_id')
            ->limit(20)
            ->pluck('run_id')
            ->values()
            ->all();
    }

    // -------------------------------------------------
    // Render
    // -------------------------------------------------
    public function render()
    {
        $this->scopeSuggestions = $this->buildScopeSuggestions();
        $this->runIdSuggestions = $this->buildRunIdSuggestions();

        return view('livewire.debug.logs.index', [
            'logs'             => $this->query()->paginate($this->perPage),
            'scopeSuggestions' => $this->scopeSuggestions,
            'runIdSuggestions' => $this->runIdSuggestions,
        ])->layout(
            'livewire.layout.app',
            [
                'breadcrumbs' => Breadcrumbs::for('debug.logs.index'),
                'tafelName'   => 'Tafel Wesseling e. V.',
                'avatarUrl'   => '/images/avatars/default-user.svg',
            ]
        );
    }
}
