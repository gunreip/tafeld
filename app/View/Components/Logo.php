<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Logo extends Component
{
    public string $variant;
    public ?string $size;
    public ?string $width;
    public ?string $height;

    public function __construct(
        string $variant = 'icon',
        string $size = null,
        string $width = null,
        string $height = null
    ) {
        $this->variant = $variant;
        $this->size = $size;
        $this->width = $width;
        $this->height = $height;
    }

    public function render()
    {
        return view('components.logo');
    }
}
