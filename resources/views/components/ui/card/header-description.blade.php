@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/card/header-description.blade.php -->
    <!-- {{ $__path }} -->
@endif

<p class="card-header-description">
    {{ $slot }}
</p>
