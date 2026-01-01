<?php

// tafeld/app/View/Components/Ui/Date/DebugRangeDatepicker.php

namespace App\View\Components\Ui\Date;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DebugRangeDatepicker extends Component
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
        return view('components.ui.date.debug-range-datepicker');
    }
}
