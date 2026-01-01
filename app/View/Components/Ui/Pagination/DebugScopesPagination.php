<?php

// tafeld/app/View/Components/Ui/Pagination/DebugScopesPagination.php

namespace App\View\Components\Ui\Pagination;

use Illuminate\View\Component;

class DebugScopesPagination extends Component
{
    public function __construct(
        public int $page,
        public int $perPage,
        public bool $hasMore,
        public ?int $total = null,
        public array $perPageOptions = [10, 20, 30, 50],
    ) {}

    public function render()
    {
        return view('components.ui.pagination.debug-scopes-pagination');
    }
}
