{{-- tafeld/resources/views/components/ui/td.blade.php --}}
{{-- table-td --}}

<td {{ $attributes->merge(['class' => 'px-4 py-2 text-default']) }}>
    {{ $slot }}
</td>
