<!-- tafeld/resources/views/livewire/layout/partials/header.blade.php -->

<header
    class="h-16 flex items-center justify-between px-4 lg:px-6
           border-b border-default bg-elevated backdrop-blur">

    <!-- LEFT SIDE: Standort + Breadcrumbs -->
    <div class="flex flex-col justify-center leading-tight select-none">

        <!-- Standortname (kommt später aus DB) -->
        <span class="mt-2 mb-2 text-base font-semibold text-brand-500 uppercase tracking-wide">
            {{ $tafelName ?? 'Tafel-Standort' }}
        </span>

    </div>

    <!-- RIGHT SIDE -->
    <div class="flex items-center gap-4">

        <!-- DARK MODE TOGGLE -->
        <button @click="toggle()"
            class="group p-2 rounded-full bg-elevated hover:bg-hover
                   text-muted hover:text-default transition">

            <x-heroicon-o-moon class="w-5 h-5 text-muted group-hover:text-default" x-show="!isDark" />
            <x-heroicon-o-sun class="w-5 h-5 text-muted group-hover:text-default" x-show="isDark" />
        </button>

        <!-- USER INFORMATION -->
        <div class="flex items-center gap-2">

            <!-- Avatar (Foto / Gender / Fallback) -->
            <img src="{{ $avatarUrl ?? '/images/avatars/default-user.svg' }}" alt="avatar"
                class="w-8 h-8 rounded-full object-cover bg-elevated">

            <!-- User Name -->
            <span class="hidden md:inline text-sm font-medium text-default">
                {{ auth()->user()->name }}
            </span>

        </div>

    </div>

</header>
