<!-- tafeld/resources/views/livewire/layout/partials/navigation.blade.php -->

@php
    use Illuminate\Support\Facades\Route;

    $navItems = [
        [
            'label' => 'Dashboard',
            'route' => 'dashboard',
            'icon' => 'home',
        ],
        [
            'label' => 'Personen',
            'route' => 'persons.index',
            'icon' => 'users',
        ],
        [
            'label' => 'Person anlegen',
            'route' => 'persons.create',
            'icon' => 'user-plus',
        ],
        [
            'label' => 'Debug',
            'route' => null,
            'icon' => 'bug',
            'children' => [
                [
                    'label' => 'Logs',
                    'route' => 'debug.logs.index',
                ],
                [
                    'label' => 'Scopes',
                    'route' => 'debug.scopes.index',
                ],
                [
                    'label' => 'Übersicht',
                    'route' => 'debug.overview',
                ],
            ],
        ],
    ];

    $currentRoute = Route::currentRouteName();
@endphp

<nav class="flex flex-col h-full">

    <!-- Brand -->
    <div class="flex items-center px-4 py-4 border-b border-default">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 m-4 text-lg font-semibold">
            <x-logo variant="die-tafeln" width="160" />
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1 px-2">

            @foreach ($navItems as $item)
                @php
                    $isActive =
                        ($item['route'] && ($currentRoute === $item['route'] || str_starts_with($currentRoute, $item['route'] . '.')));
                @endphp

                <li>
                    @if (isset($item['children']))
                        {{-- Hauptpunkt mit Untermenü --}}
                        <div
                            class="px-3 py-2 text-sm font-semibold
                                   {{ str_starts_with($currentRoute, 'debug.') ? 'text-default' : 'text-muted' }}">
                            <div class="flex items-center gap-3">
                                @if ($item['icon'] === 'bug')
                                    <x-heroicon-o-bug-ant class="w-5 h-5 text-muted" />
                                @endif
                                <span class="truncate">{{ $item['label'] }}</span>
                            </div>
                        </div>

                        <ul class="ml-8 space-y-1">
                            @foreach ($item['children'] as $child)
                                @php
                                    $childActive =
                                        $currentRoute === $child['route']
                                        || str_starts_with($currentRoute, $child['route'] . '.');
                                @endphp

                                <li>
                                    <a href="{{ route($child['route']) }}" wire:navigate
                                        class="group flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm
                                               {{ $childActive ? 'bg-active text-default font-semibold' : 'text-muted hover:bg-hover hover:text-default' }}">
                                        <span class="truncate">{{ $child['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    @else
                        {{-- Normale Menüpunkte --}}
                        <a href="{{ route($item['route']) }}" wire:navigate
                            class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                                   {{ $isActive ? 'bg-active text-default font-semibold' : 'text-muted hover:bg-hover hover:text-default' }}">

                            @switch($item['icon'])
                                @case('home')
                                    <x-heroicon-o-home
                                        class="w-5 h-5 {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                                @break

                                @case('users')
                                    <x-heroicon-o-users
                                        class="w-5 h-5 {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                                @break

                                @case('user-plus')
                                    <x-heroicon-o-user-plus
                                        class="w-5 h-5 {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                                @break

                                @case('bug')
                                    <x-heroicon-o-bug-ant
                                        class="w-5 h-5 {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                                @break
                            @endswitch

                            <span class="truncate">{{ $item['label'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach

        </ul>
    </div>

    <!-- Logout -->
    <div class="border-t border-default px-2 py-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                       text-muted hover:bg-hover hover:text-default">
                <x-heroicon-o-arrow-left-on-rectangle class="w-5 h-5 text-muted group-hover:text-default" />
                <span>Abmelden</span>
            </button>
        </form>
    </div>

</nav>
