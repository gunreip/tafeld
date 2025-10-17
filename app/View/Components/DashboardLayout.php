<?php

// app/View/Components/DashboardLayout.php
namespace App\View\Components;

use Illuminate\View\Component;

class DashboardLayout extends Component
{
    public string $title;

    public function __construct(string $title = 'Übersicht')
    {
        $this->title = $title;
    }

    public function render()
    {
        return view('components.dashboard-layout');
    }
}
