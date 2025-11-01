<div>
    @props(['active' => false])

    @php
        $classes = $active
            ? 'block pl-3 pr-4 py-2 border-l-4 border-blue-400 bg-gray-900 text-base font-medium text-blue-300 focus:outline-none focus:text-blue-300 focus:bg-gray-900 focus:border-blue-400 transition'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-300 hover:text-gray-200 hover:bg-gray-700 hover:border-gray-600 focus:outline-none focus:text-gray-200 focus:bg-gray-700 focus:border-gray-600 transition';
    @endphp

    <a {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
</div>
