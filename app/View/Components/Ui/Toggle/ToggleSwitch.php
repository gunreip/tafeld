<?php

// tafeld/app/View/Components/Ui/Toggle/ToggleSwitch.php

namespace App\View\Components\Ui\Toggle;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ToggleSwitch extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.ui.toggle.toggle-switch');
    }
}
