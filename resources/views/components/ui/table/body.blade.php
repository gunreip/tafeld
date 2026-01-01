@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/body.blade.php -->
    <!-- {{ $__path }} -->
@endif

<tbody class="divide-y divide-default">
    {{ $slot }}
</tbody>
