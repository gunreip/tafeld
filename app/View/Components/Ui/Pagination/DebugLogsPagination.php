<?php

// tafeld/app/View/Components/Ui/Pagination/DebugLogsPagination.php

namespace App\View\Components\Ui\Pagination;

use Illuminate\View\Component;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DebugLogsPagination extends Component
{
    public function __construct(
        public LengthAwarePaginator $paginator
    ) {}

    public function render()
    {
        return view('components.ui.pagination.debug-logs-pagination');
    }
}
