<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    /**
     * Array of breadcrumb items:
     * [
     *     ['label' => 'Dashboard', 'url' => route('dashboard')],
     *     ['label' => 'Personen', 'url' => route('persons.index')],
     *     ['label' => 'Neu', 'url' => null], // active item
     * ]
     */
    public array $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function render()
    {
        return view('components.breadcrumbs');
    }
}
