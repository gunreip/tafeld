@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/debug/logs/index.blade.php -->
    <!-- {{ $__path }} -->
@endif

<x-ui.section>

    <x-ui.card>

        {{-- Header --}}
        <div class="card-header">
            <div>
                <x-ui.card.header-title>
                    Debug Logs
                </x-ui.card.header-title>

                <x-ui.card.header-description>
                    Read-only · gefiltert · paginiert
                </x-ui.card.header-description>
            </div>

            <x-ui.card.header-info>
                {{ $logs->total() }} Einträge
            </x-ui.card.header-info>
        </div>

        {{-- Filter --}}
        <div class="debug-filter-bar">
            <div class="debug-filter-elements">

                {{-- Scope --}}
                <x-ui.select.debug-suggest-select
                    wire:model.debounce.300ms="scope"
                    placeholder="Scope enthält …"
                    :options="$scopeSuggestions"
                    mode="hierarchical"
                />

                {{-- Level --}}
                <x-ui.select.debug-custom-select
                    wire:model="level"
                    option-set="debug-levels"
                />

                {{-- Run-ID --}}
                <x-ui.select.debug-suggest-select
                    wire:model.debounce.300ms="run_id"
                    placeholder="Run-ID"
                    :options="$runIdSuggestions"
                    mode="flat"
                />

                {{-- Date Range (Flatpickr PoC) --}}
                <x-ui.date.debug-range-datepicker />

            </div>
        </div>

        @php
            $debugLevels = app(\App\Services\AppSettingResolver::class)
                ->getForUser(auth()->user()?->ulid, 'debug.levels', []);

            $debugLevelMap = collect($debugLevels)->keyBy('value');
        @endphp

        {{-- Tabelle --}}
        <div class="debug-table">

            <x-ui.table>

                <x-ui.table.head>
                    <x-ui.table.th>Zeit</x-ui.table.th>
                    <x-ui.table.th>Level</x-ui.table.th>
                    <x-ui.table.th>Scope</x-ui.table.th>
                    <x-ui.table.th>Message</x-ui.table.th>
                    <x-ui.table.th>Run-ID</x-ui.table.th>
                </x-ui.table.head>

                @foreach ($logs as $log)
                    <x-ui.table.tr>
                        <x-ui.table.td align="left" class="text-muted whitespace-nowrap">
                            {{ $log->created_at }}
                        </x-ui.table.td>

                        <x-ui.table.td align="left">
                            @php($meta = $debugLevelMap[$log->level] ?? null)

                            <span class="{{ $meta['class'] ?? 'text-muted' }}">
                                @if (!empty($meta['icon-name']))
                                    <x-ui.icon
                                        :name="$meta['icon-name']"
                                        class="inline-block w-4 h-4 mr-1 align-text-bottom"
                                    />
                                @endif

                                {{ $meta['label'] ?? $log->level }}
                            </span>
                        </x-ui.table.td>

                        <x-ui.table.td align="left" class="font-mono text-default">
                            {{ $log->scope }}
                        </x-ui.table.td>

                        <x-ui.table.td align="left" class="text-default">
                            {{ $log->message }}
                        </x-ui.table.td>

                        <x-ui.table.td align="left" class="font-mono text-muted">
                            {{ $log->run_id }}
                        </x-ui.table.td>
                    </x-ui.table.tr>
                @endforeach

            </x-ui.table>

        </div>

        {{-- Pagination --}}
        <x-ui.pagination.debug-logs-pagination :paginator="$logs" />

    </x-ui.card>

</x-ui.section>
