<?php

// tafeld/app/Livewire/Admin/ActivityLog/Index.php

namespace App\Livewire\Admin\ActivityLog;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public function mount(): void
    {
        Gate::authorize('app-settings.set-global');
    }

    public function render()
    {
        return view('livewire.admin.activity-log.index', [
            'activities' => Activity::query()
                ->latest()
                ->paginate(25),
        ])->layout(
            'livewire.layout.app',
            [
                'breadcrumbs' => [
                    ['label' => 'Admin'],
                    ['label' => 'Activity Log'],
                ],
            ]
        );
    }
}
