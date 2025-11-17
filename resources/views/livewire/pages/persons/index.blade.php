<!-- tafeld/resources/views/livewire/pages/persons/index.blade.php -->

<div class="max-w-5xl mx-auto py-10 px-6 bg-surface text-default">

    <h1 class="text-3xl font-semibold mb-6 text-default">Personen</h1>

    <a wire:navigate href="{{ route('persons.create') }}" class="btn-brand px-4 py-2 text-sm font-medium rounded-lg">
        Neue Person anlegen
    </a>

    @if (session()->has('message'))
        <div class="mb-4 p-3 bg-success-soft text-success rounded">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-4">
        <input type="text" wire:model.debounce.300ms="search" placeholder="Suche..."
            class="w-full px-3 py-2 rounded-md bg-card text-default border border-default
                      focus:ring-brand-500 focus:border-brand-500" />
    </div>

    <div class="bg-card shadow-sm rounded-lg p-6 border border-default">
        <table class="w-full mt-6 border-collapse">

            <thead class="sticky top-0 z-10 bg-elevated text-default border-b border-default">
                <tr>
                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('first_name')">
                        Vorname
                    </th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('last_name')">
                        Nachname
                    </th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('country_id')">
                        Land
                    </th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('email')">
                        E-Mail
                    </th>
                    <th class="px-4 py-3 font-semibold text-xs uppercase tracking-wider cursor-pointer"
                        wire:click="sortBy('phone')">
                        Telefon
                    </th>
                    <th class="px-4 py-3"></th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($people as $p)
                    <tr
                        class="border-b border-default odd:bg-card even:bg-surface text-default
                               hover:bg-hover transition-colors">
                        <td class="py-2">{{ $p->first_name }}</td>
                        <td class="py-2">{{ $p->last_name }}</td>

                        <td class="px-4 py-2 flex items-center gap-2 text-default">
                            @if ($p->country)
                                <img src="/flags/{{ strtolower($p->country->iso_3166_2) }}.svg"
                                    alt="{{ $p->country->name_de }}"
                                    class="mr-2 w-5 h-5 rounded-sm border border-default">

                                <span>{{ $p->country->name_de }}</span>
                            @else
                                -
                            @endif
                        </td>

                        <td class="py-2">{{ $p->email }}</td>
                        <td class="py-2">{{ $p->phone }}</td>

                        <td class="py-2">
                            <a wire:navigate href="{{ route('persons.edit', $p->id) }}"
                                class="btn-brand px-3 py-1 rounded-md">
                                Bearbeiten
                            </a>
                        </td>

                        <td class="py-2">
                            <form wire:submit.prevent="delete('{{ $p->id }}')"
                                onsubmit="return confirm('Eintrag wirklich löschen?')" class="inline">
                                <button type="submit" class="text-danger hover:text-danger/80 ml-2">
                                    Löschen
                                </button>
                            </form>
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-6">
        {{ $people->links() }}
    </div>

</div>
