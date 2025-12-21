<!DOCTYPE html>
<html lang="de" x-data="darkModeController()" x-init="init()">

<!-- tafeld/resources/views/livewire/layout/app.blade.php -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <title>{{ config('app.name', 'tafeld') }}</title>

    <script>
        function appLayout() {
            return {
                sidebarOpen: false,

                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },

                closeSidebar() {
                    this.sidebarOpen = false;
                },
            }
        }

        function darkModeController() {
            return {
                isDark: false,

                init() {
                    const saved = localStorage.getItem('dark-mode');
                    this.isDark = saved === 'true';
                    document.documentElement.classList.toggle('dark', this.isDark);
                },

                toggle() {
                    this.isDark = !this.isDark;
                    localStorage.setItem('dark-mode', this.isDark);
                    document.documentElement.classList.toggle('dark', this.isDark);
                },
            }
        }
    </script>

</head>

<body class="bg-surface text-default">

    <!-- Livewire Navigate Darkmode Sync -->
    <script>
        document.addEventListener("livewire:navigated", () => {
            const isDark = localStorage.getItem('dark-mode') === 'true';
            document.documentElement.classList.toggle('dark', isDark);
        });
    </script>

    <div x-data="{ sidebarOpen: false }" class="flex h-screen">

        <!-- Mobile Sidebar -->
        <div class="relative z-40 lg:hidden" x-show="sidebarOpen" x-cloak
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            {{-- @keydown.window.escape="closeSidebar()" --}}
            >

            <div class="fixed inset-0 bg-black/50" @click="closeSidebar()"></div>

            <div class="fixed inset-y-0 left-0 flex max-w-full">
                <div class="w-64 bg-elevated shadow-xl">
                    @include('livewire.layout.partials.navigation')
                </div>
            </div>
        </div>

        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex lg:flex-col w-64 shrink-0 bg-elevated border-r border-default">
            @include('livewire.layout.partials.navigation')
        </aside>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col min-w-0">

            <!-- Header (mit Breadcrumbs) -->
            @include('livewire.layout.partials.header', [
                'breadcrumbs' => $__data['breadcrumbs'] ?? null,
                'tafelName' => $__data['tafelName'] ?? null,
                'avatarUrl' => $__data['avatarUrl'] ?? null,
            ])

            {{-- Breadcrumbs unterhalb des Headers --}}
            @if (!empty($breadcrumbs))
                <div class="px-4 lg:px-6 mt-3">
                    <x-breadcrumbs :items="$breadcrumbs" />
                </div>
            @endif

            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <div class="max-w-content mx-auto w-full">
                    {{ $slot }}
                </div>
            </main>

            @include('livewire.layout.partials.footer')

        </div>

    </div>

    {{-- JS Debug Initialisierung --}}
    <script>
        // Initialisierung des Debug-Moduls (Livewire 3 kompatibel)
        document.addEventListener("livewire:init", () => {
            window.TAFELD_DEBUG_GLOBAL = @json(config('tafeld-debug'));
            window.TAFELD_DEBUG_LOG_LEVEL = "{{ env('LOG_LEVEL') }}";

            if (window.TafeldDebug && typeof window.TafeldDebug.init === 'function') {
                window.TafeldDebug.init(
                    window.TAFELD_DEBUG_GLOBAL,
                    window.TAFELD_DEBUG_LOG_LEVEL
                );
            }
        });

        // Debug-Ereignisse empfangen (Livewire 3: BrowserEvents)
        window.addEventListener('tafeld-debug', (event) => {
            if (window.TafeldDebug && typeof window.TafeldDebug.fromPHP === 'function') {
                window.TafeldDebug.fromPHP(event.detail);
            }
        });
    </script>

    @livewireScripts

</body>

</html>
