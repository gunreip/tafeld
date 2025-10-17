<div>
    <x-secure-layout>
        <div class="max-w-6xl mx-auto py-10">
            <h1 class="text-3xl font-bold mb-6 text-primary">Personalübersicht</h1>

            <div class="mb-4 flex justify-end">
                <button wire:click="create" class="btn btn-primary">+ Neu</button>
            </div>

            <table class="table w-full bg-base-200 shadow">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Abteilung</th>
                        <th>Eintritt</th>
                        <th>Status</th>
                        <th>Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($personals as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ $p->name }}</td>
                            <td>{{ $p->position }}</td>
                            <td>{{ $p->abteilung }}</td>
                            <td>{{ $p->eintrittsdatum }}</td>
                            <td>
                                <span class="badge {{ $p->aktiv ? 'badge-success' : 'badge-error' }}">
                                    {{ $p->aktiv ? 'aktiv' : 'inaktiv' }}
                                </span>
                            </td>
                            <td class="flex gap-2">
                                <button wire:click="edit({{ $p->id }})"
                                    class="btn btn-sm btn-outline">Bearbeiten</button>
                                <button wire:click="confirmDelete({{ $p->id }})"
                                    class="btn btn-sm btn-error text-white">Löschen</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Create/Edit Modal --}}
        @if ($showModal)
            <div
                class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 transition-opacity duration-300">
                <div
                    class="bg-base-100 rounded-2xl shadow-xl w-11/12 max-w-lg transform transition-all scale-100 p-6 animate-fade-in">
                    <h2 class="font-bold text-xl mb-4">{{ $editId ? 'Bearbeiten' : 'Neu anlegen' }}</h2>

                    <form wire:submit.prevent="save" class="space-y-4">
                        <div>
                            <label class="label">Name</label>
                            <input wire:model.defer="name" type="text" class="input input-bordered w-full" />
                            @error('name')
                                <span class="text-error text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="label">Position</label>
                            <input wire:model.defer="position" type="text" class="input input-bordered w-full" />
                        </div>

                        <div>
                            <label class="label">Abteilung</label>
                            <input wire:model.defer="abteilung" type="text" class="input input-bordered w-full" />
                        </div>

                        <div>
                            <label class="label">Eintrittsdatum</label>
                            <input wire:model.defer="eintrittsdatum" type="date"
                                class="input input-bordered w-full" />
                        </div>

                        <div class="flex items-center gap-2">
                            <input wire:model="aktiv" type="checkbox" class="checkbox" />
                            <span>aktiv</span>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" class="btn"
                                wire:click="$set('showModal', false)">Abbrechen</button>
                            <button type="submit" class="btn btn-primary">Speichern</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif

        {{-- Delete Modal --}}
        @if ($deleteModal)
            <div
                class="fixed inset-0 flex items-center justify-center bg-black/50 backdrop-blur-sm z-50 transition-opacity duration-300">
                <div class="bg-base-100 rounded-2xl shadow-xl w-96 p-6 text-center animate-fade-in">
                    <h3 class="font-bold text-lg mb-4">Eintrag wirklich löschen?</h3>
                    <div class="flex justify-center gap-4 mt-6">
                        <button wire:click="delete" class="btn btn-error text-white">Löschen</button>
                        <button wire:click="$set('deleteModal', false)" class="btn">Abbrechen</button>
                    </div>
                </div>
            </div>
        @endif
    </x-secure-layout>
</div>
