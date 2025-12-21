{{-- tafeld/resources/views/components/ui/table/tr.blade.php --}}

<tr {{ $attributes->merge([
    'class' => 'border-b border-default odd:bg-card even:bg-elevated hover:bg-hover transition-colors'
]) }}>
    {{ $slot }}
</tr>
