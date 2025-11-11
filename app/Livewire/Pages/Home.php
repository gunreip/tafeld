<?php

// tafeld/app/Livewire/Pages/Home.php

namespace App\Livewire\Pages;

use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('pages.home')
            ->layout('layout', ['title' => 'Startseite']);
    }
}
