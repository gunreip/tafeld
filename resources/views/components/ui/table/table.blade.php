@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/table/table.blade.php -->
    <!-- {{ $__path }} -->
@endif

<table {{ $attributes->merge(['class' => 'w-full border-collapse text-base rounded-base']) }}>

    @isset($head)
        {{ $head }}
    @endisset

    <x-ui.table.body>
        {{ $slot }}
    </x-ui.table.body>

</table>
