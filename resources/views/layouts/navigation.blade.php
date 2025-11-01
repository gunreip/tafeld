<div>
    <nav x-data="{ open: false }" class="bg-gray-800 border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                {{-- Left Section --}}
                <div class="flex">
                    {{-- Logo --}}
                    <div class="shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-400 hover:text-blue-300">
                            Tafeld
                        </a>
                    </div>

                    {{-- Navigation Links --}}
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                        <a href="{{ route('dashboard') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
                              {{ request()->routeIs('dashboard') ? 'border-blue-400 text-blue-300' : 'border-transparent text-gray-300 hover:text-gray-200 hover:border-gray-600' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('customers.create') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5
                              {{ request()->routeIs('customers.*') ? 'border-blue-400 text-blue-300' : 'border-transparent text-gray-300 hover:text-gray-200 hover:border-gray-600' }}">
                            Kunden
                        </a>
                    </div>
                </div>

                {{-- Right Section --}}
                <div class="hidden sm:flex sm:items-center sm:ml-6 gap-4">
                    {{-- Theme Toggle --}}
                    <x-theme-toggle />

                    {{-- User Dropdown --}}
                    <div class="ml-3 relative">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button
                                    class="flex items-center text-sm font-medium text-gray-300 hover:text-white focus:outline-none transition">
                                    <div>{{ Auth::user()->name ?? 'Benutzer' }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.939l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.25a.75.75 0 01-1.06 0L5.23 8.27a.75.75 0 01.02-1.06z"
                                                clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                        Abmelden
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="-mr-2 flex items-center sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
            <div class="pt-2 pb-3 space-y-1">
                <a href="{{ route('dashboard') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                      {{ request()->routeIs('dashboard') ? 'bg-gray-900 border-blue-400 text-blue-300' : 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-600' }}">
                    Dashboard
                </a>
                <a href="{{ route('customers.create') }}"
                    class="block pl-3 pr-4 py-2 border-l-4 text-base font-medium
                      {{ request()->routeIs('customers.*') ? 'bg-gray-900 border-blue-400 text-blue-300' : 'border-transparent text-gray-300 hover:bg-gray-700 hover:border-gray-600' }}">
                    Kunden
                </a>
            </div>

            {{-- Logout --}}
            <div class="pt-4 pb-1 border-t border-gray-700">
                <div class="px-4">
                    <div class="font-medium text-base text-gray-200">{{ Auth::user()->name ?? 'Benutzer' }}</div>
                    <div class="font-medium text-sm text-gray-400">{{ Auth::user()->email ?? '' }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            Abmelden
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>
