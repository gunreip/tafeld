@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/head.blade.php -->
    <!-- {{ $__path }} -->
@endif

<thead class="border-b border-default text-default bg-card">
    <tr>
        {{ $slot }}
    </tr>
</thead>
