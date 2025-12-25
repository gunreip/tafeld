@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/debug/logs/index.blade.php -->
    <!-- {{ $__path }} -->
@endif

<x-ui.section>

    <x-ui.card>

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-default">
                    Debug Logs
                </h1>
                <p class="text-sm text-muted">
                    Read-only · gefiltert · paginiert
                </p>
            </div>

            <div class="text-sm text-muted">
                {{ $logs->total() }} Einträge
            </div>
        </div>

        {{-- Filter --}}
        <div class="mt-6 bg-elevated rounded-md p-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">

                <x-ui.input.debug-input
                    wire:model.debounce.300ms="scope"
                    placeholder="Scope enthält …"
                />

                <x-ui.select.debug-custom-select
                    wire:model="level"
                    option-set="debug-levels"
                />

                <x-ui.input.debug-input
                    wire:model.debounce.300ms="run_id"
                    placeholder="Run-ID"
                    class="font-mono"
                />

                <x-ui.date.debug-datepicker
                    wire:model="from"
                />

                <x-ui.date.debug-datepicker
                    wire:model="to"
                />

            </div>
        </div>

        @php
            $debugLevels = app(\App\Services\AppSettingResolver::class)
                ->getForUser(auth()->user()?->ulid, 'debug.levels', []);

            $debugLevelMap = collect($debugLevels)->keyBy('value');
        @endphp

        {{-- Tabelle --}}
        <div class="mt-6 overflow-x-auto">

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
