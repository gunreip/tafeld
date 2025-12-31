@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/admin/app-settings/index.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div>
    <x-ui.section>

        <x-ui.card>

            {{-- Header --}}
            <div class="flex items-center gap-3 mb-6">
                <x-heroicon-o-cog-6-tooth class="w-6 h-6 text-muted" />
                <div>
                    <h1 class="text-xl font-semibold text-default">
                        App Settings
                    </h1>
                    <p class="text-sm text-muted">
                        Global · read-only · admin
                    </p>
                </div>
            </div>

            {{-- Table --}}
            <x-ui.table>

                <x-ui.table.head>
                    <x-ui.table.th>Key</x-ui.table.th>
                    <x-ui.table.th>Value</x-ui.table.th>
                    <x-ui.table.th>Updated</x-ui.table.th>
                </x-ui.table.head>

                @foreach ($settings as $setting)
                    <x-ui.table.tr>

                        <x-ui.table.td class="font-mono text-default align-top">
                            {{ $setting->key }}
                        </x-ui.table.td>

                        <x-ui.table.td class="align-top">
                            <details class="group">
                                <summary
                                    class="cursor-pointer select-none text-sm text-muted
                                           hover:text-default flex items-center gap-2"
                                >
                                    <x-heroicon-o-chevron-right
                                        class="w-4 h-4 transition-transform group-open:rotate-90"
                                    />
                                    <span>JSON anzeigen</span>
                                </summary>

                                <pre
                                    class="mt-2 p-3 rounded-md bg-elevated
                                           text-xs text-default font-mono
                                           whitespace-pre-wrap overflow-x-auto"
                                >{{ json_encode($setting->value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </details>
                        </x-ui.table.td>

                        <x-ui.table.td class="text-muted whitespace-nowrap align-top">
                            {{ $setting->updated_at }}
                        </x-ui.table.td>

                    </x-ui.table.tr>
                @endforeach

            </x-ui.table>

        </x-ui.card>

    </x-ui.section>
</div>
