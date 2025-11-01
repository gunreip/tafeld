<div>
    @props(['align' => 'right', 'width' => '48'])

    @php
        $alignmentClasses = match ($align) {
            'left' => 'origin-top-left left-0',
            'top' => 'origin-top',
            default => 'origin-top-right right-0',
        };

        $widthClass = match ($width) {
            '48' => 'w-48',
            default => 'w-48',
        };
    @endphp

    <div x-data="{ open: false }" class="relative">
        <div @click="open = ! open" class="cursor-pointer">
            {{ $trigger }}
        </div>

        <div x-show="open" x-transition @click.away="open = false"
            class="absolute z-50 mt-2 {{ $widthClass }} rounded-md shadow-lg {{ $alignmentClasses }}">
            <div class="rounded-md ring-1 ring-black ring-opacity-5 bg-white dark:bg-gray-800">
                {{ $content }}
            </div>
        </div>
    </div>
</div>
