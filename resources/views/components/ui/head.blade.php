@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/head.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- table-head --}}

<thead class="bg-elevated text-default sticky top-0 z-10">
    <tr>
        {{ $slot }}
    </tr>
</thead>
