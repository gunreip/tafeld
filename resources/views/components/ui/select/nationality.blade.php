@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/components/ui/select/nationality.blade.php -->
    <!-- {{ $__path }} -->
@endif

@props([
    'countries' => [],
    'placeholder' => 'Bitte wählen',
])

@php
    $modelValue = $attributes->wire('model')->value();
    $selectedCountry = collect($countries)->firstWhere('id', $modelValue);

    if (!$selectedCountry && app()->getLocale() === 'de') {
        $selectedCountry = collect($countries)->firstWhere('iso_3166_2', 'DE');
    }
@endphp

<div x-data="nationalitySelect({
    model: @entangle($attributes->wire('model')),
    countries: @js($countries),
    placeholder: @js($placeholder),
})" class="relative w-full">

    {{-- Button --}}
<button type="button"
        x-ref="button"
        @keydown="handleKeydown($event)"
        {{-- @click.stop="open = !open; if(open) { computeFlip(); initActiveOnOpen(); }" --}}
        @click.stop="openDropdown()"
        @keydown.enter.prevent="open = true; computeFlip(); initActiveOnOpen(); $nextTick(() => $refs.search?.focus());"
        class="select-trigger">

        <img :src="'/flags/' + (selectedFlag ?? 'xx').toLowerCase() + '.svg'"
             class="w-4 h-4 object-cover rounded-sm" alt="">

        <span class="flex-1" x-text="selectedName"></span>

        <x-heroicon-o-chevron-down class="text-muted h-4 w-4" />
    </button>

    {{-- Dropdown Panel --}}
    <div x-show="open"
         @click.outside="open = false"
         @click.outside.stop="open = false"
         x-transition
         @mousemove="handleMouseMove($event)"
         @mouseleave="handleMouseLeave()"
         data-search-container
         @wheel.prevent="handleWheel($event)"
         :class="{
             'bg-elevated border border-default absolute z-200 max-h-64 w-full overflow-auto rounded shadow-lg p-1': true,
             'mt-1 origin-top translate-y-1': !openUp,
             'mb-1 origin-bottom -translate-y-1 bottom-full': openUp
         }">

        {{-- Suchfeld --}}
        <div class="border-default border-b rounded p-0 relative">
            <input type="text"
                x-ref="search"
                class="input-base rounded mb-1 pr-8"
                placeholder="Suchen…"
                x-model="searchQuery"
                @input="updateSearch($event.target.value)"
                @keydown.escape.prevent="open = false"
                @keydown.arrow-down.prevent="moveDown()"
                @keydown.arrow-up.prevent="moveUp()"
                @keydown.enter.prevent="selectActive()"
            >

            {{-- Clear-Button (X) --}}
            <button
                type="button"
                class="absolute right-2 top-2 text-muted rounded hover:text-red-700 hover:font-semibold text"
                x-show="searchQuery"
                @click.stop="clearSearch()"
            >&times;</button>
        </div>

        {{-- Keine Treffer --}}
        <div x-show="filtered.length === 0"
             class="px-3 py-2 text-amber-500/80 animate-pulse text-base font-light flex items-center justify-center gap-2">
            <x-heroicon-o-x-circle class="h-5 w-5 text-amber-500/80" />
            <span>Keine Treffer</span>
        </div>

        {{-- Länder-Liste --}}
        <template x-for="(country, index) in filtered" :key="country.id">
            <div
                @click="choose(country)"
                data-country-item
                :class="{ 'select-active': activeIndex === index }"
                class="select-base flex items-center gap-3 hover:bg-hover hover:text-brand-500"
            >
                <img :src="'/flags/' + ((country.iso_3166_2 ?? 'xx').toLowerCase()) + '.svg'"
                    class="w-4 h-4 object-cover rounded-sm" alt="">

                <span x-html="country.highlight"></span>
            </div>
        </template>

    </div>
</div>
