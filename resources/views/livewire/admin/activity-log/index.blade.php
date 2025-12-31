@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/admin/activity-log/index.blade.php -->
    <!-- {{ $__path }} -->
@endif

<x-ui.section>

    <x-ui.card>

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <x-heroicon-o-clipboard-document-list class="w-6 h-6 text-muted" />
            <div>
                <h1 class="text-xl font-semibold text-default">
                    Activity Log
                </h1>
                <p class="text-sm text-muted">
                    System · read-only · admin
                </p>
            </div>
        </div>

        {{-- Table --}}
        <x-ui.table>

            <x-ui.table.head>
                <x-ui.table.th>Zeit</x-ui.table.th>
                <x-ui.table.th>Event</x-ui.table.th>
                <x-ui.table.th>Actor</x-ui.table.th>
                <x-ui.table.th>Subject</x-ui.table.th>
                <x-ui.table.th>Properties</x-ui.table.th>
            </x-ui.table.head>

            @foreach ($activities as $activity)
                <x-ui.table.tr>

                    <x-ui.table.td class="text-muted whitespace-nowrap">
                        {{ $activity->created_at }}
                    </x-ui.table.td>

                    <x-ui.table.td class="font-mono text-default">
                        {{ $activity->event }}
                    </x-ui.table.td>

                    <x-ui.table.td class="text-muted">
                        {{ $activity->causer_type }}
                        @if ($activity->causer_id)
                            <span class="text-muted">#{{ $activity->causer_id }}</span>
                        @endif
                    </x-ui.table.td>

                    <x-ui.table.td class="text-muted">
                        {{ $activity->subject_type }}
                        @if ($activity->subject_id)
                            <span class="text-muted">#{{ $activity->subject_id }}</span>
                        @endif
                    </x-ui.table.td>

                    <x-ui.table.td class="font-mono text-muted max-w-xl">
                        @if (!empty($activity->properties))
                            <details>
                                <summary class="cursor-pointer text-xs text-muted">
                                    JSON anzeigen
                                </summary>
                                <pre class="mt-2 text-xs whitespace-pre-wrap">
{{ json_encode($activity->properties, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                                </pre>
                            </details>
                        @else
                            <span class="text-muted">–</span>
                        @endif
                    </x-ui.table.td>

                </x-ui.table.tr>
            @endforeach

        </x-ui.table>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $activities->links() }}
        </div>

    </x-ui.card>

</x-ui.section>
