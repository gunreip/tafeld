<?php

namespace App\View\Components\Customers;

use Illuminate\View\Component;
use Illuminate\View\View;

class Form extends Component
{
    public string $action;
    public string $method;
    public $customer;

    /**
     * Create a new component instance.
     */
    public function __construct(string $action, string $method = 'POST', $customer = null)
    {
        $this->action   = $action;
        $this->method   = strtoupper($method);
        $this->customer = $customer;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.customers.form');
    }
}
