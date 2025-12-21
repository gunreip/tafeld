<!-- tafeld/resources/views/livewire/pages/persons/create.blade.php -->

<div class="py-10 px-6 bg-card rounded-lg shadow-sm border border-default text-default space-y-6">

    <h1 class="text-3xl font-semibold text-default">
        Neues Personal erfassen
    </h1>

    <form wire:submit.prevent="save" class="space-y-4" autocomplete="off">

        {{-- NEU – 3-Spalten-Zeile (Anrede / Titel / Pers.-Nr.) --}}
        <div class="flex gap-4">

            {{-- Anrede (2 Teile width) --}}
            <div class="flex-[2]">
                <x-ui.input.group label="Anrede" icon="user" labelWidth="w-48">
                    <div class="flex items-center gap-4 px-0">

                        {{-- Neue Segment-Radio-Komponente --}}
                        <x-ui.radio.segment
                            wire:model="salutation"
                            :options="[
                                'herr' => 'Herr',
                                'frau' => 'Frau',
                                'divers' => 'Divers'
                            ]"
                        />

                    </div>
                </x-ui.input.group>
            </div>

            {{-- Titel (2 Teile width) --}}
            <div class="flex-[2]">
                <x-ui.input.group label="Titel" icon="academic-cap" labelWidth="w-28">
                    <x-ui.secure.text-autocomplete-off wire:model="title" placeholder="Titel" />
                </x-ui.input.group>
            </div>

            {{-- Pers.-Nr. (1 Teil width) --}}
            <div class="flex-[1.5]">
                <x-ui.input.group label="Pers.-Nr." icon="identification" labelWidth="w-28">
                    <x-ui.secure.text-autocomplete-off wire:model="personal_number" placeholder="Pers.-Nr." readonly />
                </x-ui.input.group>
            </div>

        </div>

        {{-- Vorname + Nachname --}}
        <x-ui.input.group label="Vor- / Nachname" icon="user" labelWidth="w-48">
            <div class="grid grid-cols-2 divide-x input-group-divider">
                <x-ui.secure.text-autocomplete-off wire:model="first_name" placeholder="Vorname" />
                <x-ui.secure.text-autocomplete-off wire:model="last_name" placeholder="Nachname" />
            </div>
        </x-ui.input.group>

        {{-- Straße & Hausnummer --}}
        <x-ui.input.group label="Straße & Hausnummer" icon="home" labelWidth="w-48">
            <div class="grid grid-cols-[3fr_1fr] divide-x input-group-divider">
                <x-ui.secure.text-autocomplete-off wire:model="street" placeholder="Straße" />
                <x-ui.secure.text-autocomplete-off wire:model="house_number" placeholder="Nr." />
            </div>
        </x-ui.input.group>

        {{-- Land / PLZ / Ort --}}
        <x-ui.input.group label="Land / PLZ / Ort" icon="map" labelWidth="w-48">
            <div class="grid grid-cols-[1fr_1fr_3fr] divide-x input-group-divider">
                <x-ui.secure.text-autocomplete-off wire:model="iso3166_3" placeholder="DEU" />
                <x-ui.secure.text-autocomplete-off wire:model="zipcode" placeholder="Postleitzahl" />
                <x-ui.secure.text-autocomplete-off wire:model="city" placeholder="Wohnort" />
            </div>
        </x-ui.input.group>

        {{-- NEU: Kombinierter Telefonblock --}}
        <x-ui.input.group label="Telefon Festnetz / Mobil" icon="phone" labelWidth="w-48">
            <div class="grid grid-cols-[auto_1fr_1fr_2fr_auto_1fr_1fr_2fr] divide-x input-group-divider items-center">

                <div class="h-full flex items-center justify-center px-3 bg-elevated">
                    <x-ui.icon name="phone" size="lg" />
                </div>

                <x-ui.secure.text-autocomplete-off wire:model="landline_country" placeholder="+49" />
                <x-ui.secure.text-autocomplete-off wire:model="landline_area" placeholder="030" />
                <x-ui.secure.text-autocomplete-off wire:model="landline_number" placeholder="1234567" />

                <div class="h-full flex items-center justify-center px-3 bg-elevated">
                    <x-ui.icon name="device-phone-mobile" size="lg" />
                </div>

                <x-ui.secure.text-autocomplete-off wire:model="mobile_country" placeholder="+49" />
                <x-ui.secure.text-autocomplete-off wire:model="mobile_area" placeholder="0176" />
                <x-ui.secure.text-autocomplete-off wire:model="mobile_number" placeholder="1234567" />

            </div>
        </x-ui.input.group>

        {{-- Geboren am / Beschäftigung – NEU --}}
        <x-ui.input.group label="Geb. am / Beschäftigung" icon="calendar" labelWidth="w-48">
            <div class="grid grid-cols-[2fr_auto_2fr_auto_2fr] divide-x input-group-divider">

                <x-ui.date.datepicker
                    wire:model="birthdate"
                    placeholder="Geboren am ..."
                    :holidays="$holidays"
                />

                <div class="flex items-center justify-end px-3 bg-elevated text-default text-sm min-w-[62px]">
                    Seit
                </div>

                <x-ui.secure.text-autocomplete-off wire:model="employment_start" type="date" placeholder="Beginn" />

                <div class="flex items-center justify-end px-3 bg-elevated text-default text-sm min-w-[62px]">
                    Bis
                </div>

                <x-ui.secure.text-autocomplete-off wire:model="employment_end" type="date" placeholder="Beendet" />

            </div>
        </x-ui.input.group>

        {{-- Staatsangehörigkeit + Arbeitserlaubnis --}}
        <div class="flex gap-4">

            <div class="flex-[2]">
                <x-ui.input.group label="Staatsangehörigkeit" icon="flag" labelWidth="w-48">
                    <div>
                        <x-ui.select.nationality :countries="$countries" wire:model="nationality" />
                    </div>
                </x-ui.input.group>
            </div>

            <div class="flex-[3]">
                <x-ui.input.group label="Arbeitserlaubnis" icon="document" labelWidth="w-48">
                    <div class="grid grid-cols-[auto_1fr_auto_2fr] divide-x input-group-divider">

                        {{-- WorkArea-Badge --}}
                        <div class="flex items-center justify-center px-3">
                            @if($nationality_work_area)
                                <x-ui.badge.work-area :value="$nationality_work_area" />
                            @endif
                        </div>

                        {{-- Neuer Toggle-Switch --}}
                        <div class="flex items-center justify-center px-6">
                            <x-ui.toggle.toggle-switch wire:model="work_permit" />
                        </div>

                        <div class="flex items-center justify-center px-3 bg-elevated text-default">
                            <x-ui.icon name="calendar" size="lg" />
                        </div>

                        <x-ui.secure.text-autocomplete-off wire:model="work_permit_until" type="date"
                            placeholder="Gültig bis" />

                    </div>
                </x-ui.input.group>
            </div>

            {{-- <div id="debug-settings-dump"
                data-debug-settings='@json($debugSettingsTest)'></div> <!-- DEBUG-TEST --> --}}

        </div>

        {{-- Buttons --}}
        <div class="flex justify-end space-x-2 mt-6">

            <a href="{{ route('persons.index') }}"
                class="px-4 py-2 text-sm font-medium bg-elevated text-default border border-default rounded-lg hover:bg-hover">
                Abbrechen
            </a>

            <button type="submit" class="btn-brand px-4 py-2 text-sm font-medium rounded-lg">
                Speichern
            </button>

        </div>

    </form>

</div>

<!-- DEBUG-TEST -->
{{-- <script>
    console.group("[TAFELD DEBUG COLLECTOR]");
    console.log(@js(\App\Support\TafeldDebug\Collector::all()));
    console.groupEnd();
</script> --}}