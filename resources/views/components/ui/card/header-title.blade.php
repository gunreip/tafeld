@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/card/header-title.blade.php -->
    <!-- {{ $__path }} -->
@endif

<h1 class="card-header-title">
    {{ $slot }}
</h1>
