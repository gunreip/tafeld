<?php

namespace App\Support;

class Breadcrumbs
{
    public static function for(string $routeName): array
    {
        return match ($routeName) {

            'dashboard' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'url' => route('dashboard')],
            ],

            'persons.index' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'url' => route('dashboard')],
                ['label' => 'Personen', 'icon' => 'users', 'url' => null],
            ],

            'persons.create' => [
                ['label' => 'Dashboard', 'icon' => 'home', 'url' => route('dashboard')],
                ['label' => 'Personen', 'icon' => 'users', 'url' => route('persons.index')],
                ['label' => 'Neu', 'icon' => 'plus', 'url' => null],
            ],

            default => [],
        };
    }
}
