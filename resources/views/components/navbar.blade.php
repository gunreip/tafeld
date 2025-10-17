<div>
    <header class="navbar bg-base-200 border-b border-base-300">
        <div class="flex-1 px-6 text-lg font-semibold capitalize">
            {{ $title ?? 'Übersicht' }}
        </div>
        <div class="flex-none flex items-center gap-3 pr-6">
            <x-heroicon-s-user class="w-6 h-6 text-primary" />
            <span>{{ auth()->user()->name ?? 'Gast' }}</span>
        </div>
    </header>
</div>
