{{-- tafeld/resources/views/components/ui/table/table.blade.php --}}

<table {{ $attributes->merge(['class' => 'w-full border-collapse text-base rounded-base']) }}>

    @isset($head)
        {{ $head }}
    @endisset

    <x-ui.table.body>
        {{ $slot }}
    </x-ui.table.body>

</table>
