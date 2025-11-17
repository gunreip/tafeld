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
                        $currentRoute === $item['route'] || str_starts_with($currentRoute, $item['route'] . '.');
                @endphp

                <li>
                    <a href="{{ route($item['route']) }}" wire:navigate
                        class="group flex items-center gap-3 px-3 py-2 rounded-lg text-sm
                            {{ $isActive ? 'bg-active text-default font-semibold' : 'text-muted hover:bg-hover hover:text-default' }}">

                        @switch($item['icon'])
                            @case('home')
                                <x-heroicon-o-home
                                    class="w-5 h-5
                                        {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                            @break

                            @case('users')
                                <x-heroicon-o-users
                                    class="w-5 h-5
                                        {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                            @break

                            @case('user-plus')
                                <x-heroicon-o-user-plus
                                    class="w-5 h-5
                                        {{ $isActive ? 'text-default' : 'text-muted group-hover:text-default' }}" />
                            @break
                        @endswitch

                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
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
