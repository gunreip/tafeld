{{-- tafeld/resources/views/livewire/debug/logs/index.blade.php --}}

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

                <input
                    type="text"
                    wire:model.debounce.300ms="scope"
                    placeholder="Scope enthält …"
                    class="input-base rounded-md"
                >

                <select wire:model="level" class="input-base rounded-md">
                    <option value="">Level (alle)</option>
                    <option value="debug">debug</option>
                    <option value="info">info</option>
                    <option value="warning">warning</option>
                    <option value="error">error</option>
                    <option value="critical">critical</option>
                </select>

                <input
                    type="text"
                    wire:model.debounce.300ms="run_id"
                    placeholder="Run-ID"
                    class="input-base font-mono rounded-md"
                >

                <input
                    type="datetime-local"
                    wire:model="from"
                    class="input-base rounded-md"
                >

                <input
                    type="datetime-local"
                    wire:model="to"
                    class="input-base rounded-md"
                >
            </div>
        </div>

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

                        {{-- Zeit --}}
                        <x-ui.table.td align="left" class="text-muted font-mono whitespace-nowrap">
                            {{ $log->created_at }}
                        </x-ui.table.td>

                        {{-- Level --}}
                        <x-ui.table.td align="left">
                            <span @class([
                                'text-muted'    => $log->level === 'debug',
                                'text-info'     => $log->level === 'info',
                                'text-warning'  => $log->level === 'warning',
                                'text-danger'   => in_array($log->level, ['error', 'critical']),
                                'font-semibold' => $log->level === 'critical',
                            ])>
                                {{ $log->level }}
                            </span>
                        </x-ui.table.td>

                        {{-- Scope --}}
                        <x-ui.table.td align="left" class="font-mono text-default">
                            {{ $log->scope }}
                        </x-ui.table.td>

                        {{-- Message --}}
                        <x-ui.table.td align="left" class="text-default">
                            {{ $log->message }}
                        </x-ui.table.td>

                        {{-- Run-ID --}}
                        <x-ui.table.td align="left" class="font-mono text-muted">
                            {{ $log->run_id }}
                        </x-ui.table.td>

                    </x-ui.table.tr>
                @endforeach

            </x-ui.table>

        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            <x-ui.pagination :paginator="$logs" />
        </div>

    </x-ui.card>

</x-ui.section>
