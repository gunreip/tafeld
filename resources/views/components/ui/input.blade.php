@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/input.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'type' => 'text',
    'iconLeft' => null,
    'iconRight' => null,
    'showPassword' => false,
])

<div class="relative w-full" x-data>
    
    {{-- Left Icon (optional) --}}
    @if ($iconLeft)
        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-muted">
            {{ $iconLeft }}
        </span>
    @endif

    {{-- Input Field --}}
    <input type="{{ $type }}" @if($showPassword) x-ref="input" @endif
        {{ $attributes->merge([
            'class' =>
                'rounded px-3 py-2 bg-card text-default border border-default
                                         focus:ring-brand-500 focus:border-brand-500 w-full
                                         ' .
                ($iconLeft ? 'pl-10 ' : '') .
                ($iconRight || $showPassword ? 'pr-10 ' : ''),
        ]) }} />

    {{-- Right Icon (optional) --}}
    @if ($iconRight)
        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted">
            {{ $iconRight }}
        </span>
    @endif

    {{-- Show / Hide Password --}}
    @if ($showPassword)
        <span class="absolute inset-y-0 right-0 pr-3 flex items-center text-muted">
            <span x-data="{ show: false }" class="flex items-center">
                <button type="button"
                    @click="show = !show; if ($refs.input) { $refs.input.type = show ? 'text' : 'password' }; $el.setAttribute('aria-pressed', show)"
                    x-bind:aria-label="show ? 'Passwort verbergen' : 'Passwort anzeigen'"
                    class="rounded p-2 text-muted hover:text-default focus:outline-none focus:ring-2 focus:ring-brand-500"
                >
                    <x-ui.icon name="eye" x-show="!show" class="w-4 h-4" />
                    <x-ui.icon name="eye-off" x-show="show" class="w-4 h-4" />
                </button>
            </span>
        </span>
    @endif
</div>
