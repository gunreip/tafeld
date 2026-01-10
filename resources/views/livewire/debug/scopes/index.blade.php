@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/debug/scopes/index.blade.php -->
    <!-- {{ $__path }} -->
@endif

<x-ui.section>

    <x-ui.card>

        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-default">
                    Debug Scopes
                </h1>
                <p class="text-sm text-muted">
                    Aktivierung/Deaktivierung einzelner Debug-Bereiche. <span class="text-warning">Blacklist beachten!</span> <code>debug.*</code>, <code>ui.*</code> und <code>livewire.*</code>
                </p>
            </div>

            <div class="text-sm text-muted">
                {{ $this->scopes->count() }} Scopes
            </div>
        </div>

        {{-- Tabelle --}}
        <div class="mt-6 overflow-x-auto">

            <x-ui.table>

                <x-ui.table.head>
                    <x-ui.table.th sortable="true">Scope</x-ui.table.th>
                    <x-ui.table.th>Status</x-ui.table.th>
                    <x-ui.table.th>Runtime-Kill</x-ui.table.th>
                    <x-ui.table.th>First seen</x-ui.table.th>
                    <x-ui.table.th>Last seen</x-ui.table.th>
                </x-ui.table.head>

                @foreach ($this->scopes as $scope)
                    <x-ui.table.tr wire:key="scope-{{ $scope->id }}">

                        {{-- Scope --}}
                        <x-ui.table.td align="left" class="font-mono text-default">
                            {{ $scope->scope_key }}
                        </x-ui.table.td>

                        {{-- Status --}}
                        <x-ui.table.td align="center">
                            <x-ui.toggle.toggle-switch
                                :disabled="false"
                                wire:model.live="enabled.{{ $scope->id }}"
                            />
                        </x-ui.table.td>

                        {{-- Runtime-Kill --}}
                        <x-ui.table.td align="center">
                            <x-ui.toggle.toggle-switch
                                :disabled="false"
                                wire:model.live="runtimeKill.{{ $scope->id }}"
                            />
                        </x-ui.table.td>

                        {{-- First seen --}}
                        <x-ui.table.td align="left" class="text-muted whitespace-nowrap">
                            {{ $scope->first_seen_at }}
                        </x-ui.table.td>

                        {{-- Last seen --}}
                        <x-ui.table.td align="left" class="text-muted whitespace-nowrap">
                            {{ $scope->last_seen_at }}
                        </x-ui.table.td>

                    </x-ui.table.tr>
                @endforeach

            </x-ui.table>

        </div>

    </x-ui.card>

</x-ui.section>
