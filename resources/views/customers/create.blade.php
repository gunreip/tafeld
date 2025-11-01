<x-app-layout>
    {{-- Header analog zum Dashboard --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Kundenerfassung
        </h2>
    </x-slot>

    {{-- Hauptinhalt --}}
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900 dark:text-gray-100">
                <x-customers.alerts />
                <x-customers.form :action="route('customers.store')" method="POST" />
            </div>
        </div>
    </div>
</x-app-layout>
