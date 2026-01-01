<?php

// tafeld/app/View/Components/Ui/Select/DebugCustomSelect.php

namespace App\View\Components\Ui\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Services\UiPreferenceResolver;
use App\Services\AppSettingResolver;

class DebugCustomSelect extends Component
{
    public string $optionSet;
    public array $resolvedOptions = [];
    public ?string $value;
    public ?array $currentOption = null;
    protected UiPreferenceResolver $uiPreferenceResolver;
    protected AppSettingResolver $appSettingResolver;
    public string $keyboardEnterBehavior;

    /**
     * Create a new component instance.
     */
    public function __construct(
        UiPreferenceResolver $uiPreferenceResolver,
        AppSettingResolver $appSettingResolver,
        string $optionSet = '',
        ?string $value = null
    ) {
        $this->uiPreferenceResolver = $uiPreferenceResolver;
        $this->appSettingResolver  = $appSettingResolver;
        $this->optionSet = $optionSet;
        $this->value = $value;
        $this->resolvedOptions = $this->resolveOptions($optionSet);

        if ($this->value !== null) {
            foreach ($this->resolvedOptions as $option) {
                if ($option['value'] === $this->value) {
                    $this->currentOption = $option;
                    break;
                }
            }
        }

        // Resolve UI preference (User → Global → Fallback)
        $this->keyboardEnterBehavior = $this->uiPreferenceResolver->resolve(
            scope: 'debug',
            key: 'keyboard.enter_behavior',
            userUlid: auth()->user()?->ulid,
            fallback: 'stay'
        );
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.select.debug-custom-select', [
            'options' => $this->resolvedOptions,
            'keyboardEnterBehavior' => $this->keyboardEnterBehavior,
            'currentOption' => $this->currentOption,
        ]);
    }

    protected function resolveOptions(string $key): array
    {
        return match ($key) {
            'debug-levels' => $this->appSettingResolver->getForUser(
                auth()->user()?->ulid,
                'debug.levels',
                []
            ),
            default => [],
        };
    }
}
