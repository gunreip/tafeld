<?php

// tafeld/app/View/Components/Ui/Select/DebugSelect.php

namespace App\View\Components\Ui\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DebugSelect extends Component
{
    public string $optionSet;
    public array $resolvedOptions = [];

    /**
     * Create a new component instance.
     */
    public function __construct(string $optionSet = '')
    {
        $this->optionSet = $optionSet;
        $this->resolvedOptions = $this->resolveOptions($optionSet);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.select.debug-select', [
            'options' => $this->resolvedOptions,
        ]);
    }

    protected function resolveOptions(string $key): array
    {
        return match ($key) {
            'debug-levels' => [
                [
                    'value'    => '',
                    'label'    => 'Level (alle)',
                    'class'    => 'text-muted',
                    'semantic' => 'all',
                ],
                [
                    'value' => 'debug',
                    'label' => 'debug',
                    'class' => 'text-muted',
                ],
                [
                    'value' => 'info',
                    'label' => 'info',
                    'class' => 'text-info',
                ],
                [
                    'value' => 'warning',
                    'label' => 'warning',
                    'class' => 'text-warning',
                ],
                [
                    'value' => 'error',
                    'label' => 'error',
                    'class' => 'text-danger',
                ],
                [
                    'value' => 'critical',
                    'label' => 'critical',
                    'class' => 'text-danger font-semibold',
                ],
            ],
            default => [],
        };
    }
}
