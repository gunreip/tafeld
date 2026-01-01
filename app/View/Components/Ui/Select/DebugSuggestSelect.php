<?php

// tafeld/app/View/Components/Ui/Select/DebugSuggestSelect.php

namespace App\View\Components\Ui\Select;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DebugSuggestSelect extends Component
{
    public array $options;
    public ?string $placeholder;

    public function __construct(array $options = [], ?string $placeholder = null)
    {
        $this->options = $options;
        $this->placeholder = $placeholder;
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.select.debug-suggest-select');
    }
}
