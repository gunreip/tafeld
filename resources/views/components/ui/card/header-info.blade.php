@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/card/header-info.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div class="card-header-info">
    {{ $slot }}
</div>
