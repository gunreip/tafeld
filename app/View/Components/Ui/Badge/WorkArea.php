<?php

// tafeld/app/View/Components/Ui/Badge/WorkArea.php

namespace App\View\Components\Ui\Badge;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Enums\CountryWorkArea;

class WorkArea extends Component
{
    /**
     * WorkArea Enum-Wert (oder null).
     */
    public ?CountryWorkArea $workArea = null;

    /**
     * Derived display label.
     */
    public string $label;

    /**
     * Derived color style class.
     */
    public string $color;

    /**
     * Create a new component instance.
     */
    public function __construct(?CountryWorkArea $workArea = null)
    {
        $this->workArea = $workArea;

        // Label-Mapping
        $this->label = match ($workArea) {
            CountryWorkArea::EU_EEA_SWISS   => 'EU / EWR / CH',
            CountryWorkArea::PRIVILEGED     => 'Privilegiert',
            CountryWorkArea::THIRD_COUNTRY  => 'Drittstaat',
            default                         => '–',
        };

        // Farb-Mapping (verwenden die CSS-Klassen aus badge.css)
        $this->color = match ($workArea) {
            CountryWorkArea::EU_EEA_SWISS   => 'badge-workarea-eu',
            CountryWorkArea::PRIVILEGED     => 'badge-workarea-priv',
            CountryWorkArea::THIRD_COUNTRY  => 'badge-workarea-third',
            default                         => 'text-default',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.badge.work-area');
    }
}
