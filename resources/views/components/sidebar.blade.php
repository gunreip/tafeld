<div>
    <aside class="w-64 bg-neutral text-neutral-content flex flex-col justify-between shadow-lg">
        <div>
            <div class="flex items-center gap-2 px-4 py-5 text-xl font-bold border-b border-base-300">
                <x-heroicon-s-rectangle-stack class="w-6 h-6 text-primary" />
                tafeld
            </div>
            <ul class="menu p-2">
                <li>
                    <a href="{{ route('dashboard') }}"
                        class="{{ request()->routeIs('dashboard') ? 'active font-bold bg-primary text-white' : '' }}">
                        <x-heroicon-o-home class="w-5 h-5" /> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('personal.index') }}"
                        class="{{ request()->routeIs('personal.*') ? 'active font-bold bg-primary text-white' : '' }}">
                        <x-heroicon-o-user-group class="w-5 h-5" /> Personal
                    </a>
                </li>
                <li class="opacity-50 cursor-not-allowed">
                    <x-heroicon-o-briefcase class="w-5 h-5" /> Kunden
                </li>
                <li class="opacity-50 cursor-not-allowed">
                    <x-heroicon-o-banknotes class="w-5 h-5" /> Finanzen
                </li>
            </ul>
        </div>

        <div class="p-4 border-t border-base-300 flex items-center justify-between">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-sm btn-error text-white">
                    <x-heroicon-o-arrow-right-on-rectangle class="w-5 h-5" /> Logout
                </button>
            </form>
        </div>
    </aside>
</div>
