<!-- tafeld/resources/views/livewire/pages/persons/edit.blade.php -->

<div class="max-w-3xl mx-auto py-10 px-6 bg-card rounded-lg shadow-sm border border-default text-default space-y-6">

    <h1 class="text-3xl font-semibold text-default">
        Person bearbeiten
    </h1>

    <form wire:submit.prevent="save" class="space-y-4">

        {{-- Vorname --}}
        <div>
            <label class="block mb-1 text-default">Vorname:</label>
            <input type="text" wire:model="first_name"
                class="w-full rounded-md px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500">
        </div>

        {{-- Nachname --}}
        <div>
            <label class="block mb-1 text-default">Nachname:</label>
            <input type="text" wire:model="last_name"
                class="w-full rounded-md px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500">
        </div>

        {{-- Email --}}
        <div>
            <label class="block mb-1 text-default">E-Mail:</label>
            <input type="email" wire:model="email"
                class="w-full rounded-md px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500">
        </div>

        {{-- Phone --}}
        <div>
            <label class="block mb-1 text-default">Telefon:</label>
            <input type="text" wire:model="phone"
                class="w-full rounded-md px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500">
        </div>

        {{-- Buttons --}}
        <div class="flex justify-end space-x-2 mt-6">

            {{-- Abbruch-Button --}}
            <a href="{{ route('persons.index') }}"
                class="px-4 py-2 text-sm font-medium bg-elevated text-default border border-default rounded-lg hover:bg-hover">
                Abbrechen
            </a>

            {{-- Speichern-Button --}}
            <button type="submit" class="btn-brand px-4 py-2 text-sm font-medium rounded-lg">
                Speichern
            </button>

        </div>

    </form>

</div>
