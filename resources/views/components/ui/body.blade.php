@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/body.blade.php -->
    <!-- {{ $__path }} -->
@endif

{{-- table-body --}}

<tbody class="bg-card">
    {{ $slot }}
</tbody>
