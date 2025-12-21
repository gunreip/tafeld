{{-- tafeld/resources/views/livewire/debug/scopes/show.blade.php --}}

<div class="space-y-10">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            Scope: {{ $scope['key'] }}
        </h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Detailansicht dieses Debug-Scopes.
        </p>
    </div>

    {{-- FILTER --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 p-6 bg-white dark:bg-gray-800 rounded-lg shadow">

        {{-- Level --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Level</label>
            <select wire:model="level"
                class="w-full rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-2">
                <option value="all">Alle</option>
                <option value="debug">Debug</option>
                <option value="info">Info</option>
                <option value="warn">Warn</option>
                <option value="error">Error</option>
                <option value="success">Success</option>
                <option value="fail">Fail</option>
            </select>
        </div>

        {{-- Zeitraum --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Zeitraum</label>
            <select wire:model="period"
                class="w-full rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-2">
                <option value="24h">Letzte 24h</option>
                <option value="7d">Letzte 7 Tage</option>
                <option value="30d">Letzte 30 Tage</option>
                <option value="all">Alles</option>
            </select>
        </div>

        {{-- Suchfeld --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Suche</label>
            <input type="text" wire:model="search"
                   class="w-full rounded-md bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 p-2"
                   placeholder="Textsuche...">
        </div>
    </div>

    {{-- CHART: Logs nach Level --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
    {{-- <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow h-[450px] overflow-hidden"> --}}

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Logs nach Level (gefiltert)
        </h2>

        <canvas
            id="chart-scope-level"
            wire:key="chart-scope-level-{{ md5(json_encode($chartByLevel)) }}"
            class="w-full h-[350px]"
            data-chart='@json($chartByLevel)'>
        </canvas>
    </div>

    {{-- LOG-TABELLE --}}
    <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">

        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Logs ({{ count($logs) }})
        </h2>

        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-300">
                    <th class="py-2">Zeit</th>
                    <th class="py-2">Level</th>
                    <th class="py-2">Message</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($logs as $log)
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <td class="py-2 text-gray-400">{{ $log['time'] }}</td>
                        <td class="py-2">{{ strtoupper($log['level']) }}</td>
                        <td class="py-2">{{ $log['message'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 text-gray-400 text-center">
                            Keine Logs für die aktuelle Filterung.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>

</div>
