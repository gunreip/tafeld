<?php

// tafeld/app/Livewire/Debug/Logs/Index.php

namespace App\Livewire\Debug\Logs;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Support\Breadcrumbs;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    // Filter
    public string $scope = '';
    public string $level = '';
    public string $run_id = '';
    public ?string $from = null;
    public ?string $to = null;

    public function updating($name): void
    {
        // Filteränderungen setzen die Pagination zurück
        $this->resetPage();
    }

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
            $q->where('created_at', '>=', $this->from);
        }

        if ($this->to) {
            $q->where('created_at', '<=', $this->to);
        }

        return $q;
    }

    public function render()
    {
        return view('livewire.debug.logs.index', [
            'logs' => $this->query()->paginate(50),
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
