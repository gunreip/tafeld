@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/secure/text-autocomplete-off.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'type' => 'text',
    'id' => null,
    'name' => null,
])

@php
    use Illuminate\Support\Str;
@endphp

<input
    {{ $attributes->merge([
        'type' => $type,
        'id' => $id,
        'name' => $name ?? 'nohistory_' . ($id ?? Str::uuid()),
        'class' => 'input-base',
        'form' => '__none__',
        'autocomplete' => 'off',
        'autocorrect' => 'off',
        'autocapitalize' => 'off',
        'spellcheck' => 'false',
    ]) }} />
