{{-- <div> --}}
<x-app-layout>
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-100">Kundenerfassung</h1>
    </x-slot>

    <div class="max-w-5xl mx-auto py-8 px-6">
        {{-- Feedback --}}
        <x-customers.alerts />

        {{-- Kundenformular --}}
        <x-customers.form :action="route('customers.store')" method="POST" />
    </div>
</x-app-layout>
{{-- </div> --}}
