<?php

// tafeld/app/Livewire/Admin/AppSettings/Index.php

namespace App\Livewire\Admin\AppSettings;

use Livewire\Component;
use Illuminate\Support\Facades\Gate;
use App\Models\AppSetting;

class Index extends Component
{
    public $settings;

    public function mount(): void
    {
        Gate::authorize('app-settings.set-global');

        $this->settings = AppSetting::query()
            ->orderBy('key')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.app-settings.index')
            ->layout(
                'livewire.layout.app',
                [
                    'breadcrumbs' => [
                        ['label' => 'Admin'],
                        ['label' => 'App Settings'],
                    ],
                    'tafelName' => 'Tafel Wesseling e. V.',
                    'avatarUrl' => '/images/avatars/default-user.svg',
                ]
            );
    }
}
