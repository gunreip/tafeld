{{-- tafeld/resources/views/livewire/debug/overview.blade.php --}}

<div>
    {{-- Debug Overview Dashboard --}}

    {{-- ============================= --}}
    {{-- KACHELN: STATISTIKEN --}}
    {{-- ============================= --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-sm text-gray-500">Gesamt Scopes</div>
            <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $stats['total_scopes'] }}
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-sm text-gray-500">Aktive Scopes</div>
            <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $stats['active_scopes'] }}
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="text-sm text-gray-500">Logs (24h)</div>
            <div class="text-3xl font-semibold text-gray-900 dark:text-gray-100">
                {{ $stats['logs_24h'] }}
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- CHARTS --}}
    {{-- ============================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 mb-10">

        {{-- Chart: Logs nach Level --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                Logs nach Level (24h)
            </h2>
            <canvas id="chart-logs-level"
                class="w-full h-[350px]"
                data-chart='@json($chartLogsByLevel)'>
            </canvas>
        </div>

        {{-- Chart: Logs nach Scope --}}
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
                Logs nach Scope (24h)
            </h2>
            <canvas id="chart-logs-scope"
                class="w-full h-[350px]"
                data-chart='@json($chartLogsByScope)'>
            </canvas>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- LETZTE LOG-EINTRÄGE --}}
    {{-- ============================= --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">
            Letzte Logs
        </h2>

        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700">
                    <th class="py-2">Zeit</th>
                    <th class="py-2">Level</th>
                    <th class="py-2">Scope</th>
                    <th class="py-2">Message</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($latestLogs as $log)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-1">{{ $log['time'] }}</td>
                        <td class="py-1">{{ strtoupper($log['level']) }}</td>
                        <td class="py-1">{{ $log['scope'] }}</td>
                        <td class="py-1">{{ $log['message'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
