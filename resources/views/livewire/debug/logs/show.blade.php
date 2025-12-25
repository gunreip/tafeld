@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/debug/logs/show.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div class="p-6 space-y-6">

    <h1 class="text-2xl font-semibold">
        Debug-Run {{ $logs->first()?->run_id }}
    </h1>

    <div class="space-y-3">
        @foreach ($logs as $log)
            <div class="border border-default rounded-lg p-4">

                <div class="flex justify-between text-sm text-muted">
                    <span>{{ $log->created_at }}</span>
                    <span class="font-mono">{{ $log->scope }}</span>
                </div>

                <div class="mt-2 font-semibold">
                    {{ $log->message }}
                </div>

                @if (!empty($log->context))
                    <pre class="mt-3 text-xs bg-card p-3 rounded overflow-x-auto">
{{ json_encode($log->context, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}
                    </pre>
                @endif

            </div>
        @endforeach
    </div>

</div>
