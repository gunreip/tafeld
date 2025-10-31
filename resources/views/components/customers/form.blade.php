<div>
    <form method="{{ $method === 'GET' ? 'GET' : 'POST' }}" action="{{ $action }}" enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @if (!in_array($method, ['GET', 'POST']))
            @method($method)
        @endif

        {{-- Abschnitt: Persönliche Daten --}}
        <section class="bg-gray-900 p-4 rounded-lg border border-gray-800">
            <h2 class="text-lg font-semibold text-gray-100 mb-4">Persönliche Daten</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-customers.inputs.salutation :value="old('salutation', $customer->salutation ?? '')" />
                <x-customers.inputs.title :value="old('title', $customer->title ?? '')" />
                <x-customers.inputs.customer-number :value="old('customer_number', $customer->customer_number ?? '')" />
                <x-customers.inputs.first-name :value="old('first_name', $customer->first_name ?? '')" />
                <x-customers.inputs.last-name :value="old('last_name', $customer->last_name ?? '')" />
                <x-customers.inputs.birth-name :value="old('birth_name', $customer->birth_name ?? '')" />
                <x-customers.inputs.birth-date :value="old('birth_date', $customer->birth_date ?? '')" />
                <x-customers.inputs.birth-country :value="old('birth_country', $customer->birth_country ?? '')" />
                <x-customers.inputs.birth-city :value="old('birth_city', $customer->birth_city ?? '')" />
                <x-customers.inputs.nationality :value="old('nationality', $customer->nationality ?? '')" />
            </div>
        </section>

        {{-- Abschnitt: Ausweis- und Familiendaten --}}
        <section class="bg-gray-900 p-4 rounded-lg border border-gray-800">
            <h2 class="text-lg font-semibold text-gray-100 mb-4">Ausweis- und Familiendaten</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-customers.inputs.identity-document :value="old('identity_document', $customer->identity_document ?? '')" />
                <x-customers.inputs.family-status :value="old('family_status', $customer->family_status ?? '')" />
                <x-customers.inputs.religion :value="old('religion', $customer->religion ?? '')" />
                <x-customers.inputs.household-number :value="old('household_number', $customer->household_number ?? '')" />
                <x-customers.inputs.responsible-office :value="old('responsible_office', $customer->responsible_office ?? '')" />
                <x-customers.inputs.household-person-count :value="old('household_person_count', $customer->household_person_count ?? '')" />
            </div>
        </section>

        {{-- Abschnitt: Status & Verwaltung --}}
        <section class="bg-gray-900 p-4 rounded-lg border border-gray-800">
            <h2 class="text-lg font-semibold text-gray-100 mb-4">Status & Verwaltung</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-customers.inputs.valid-until :value="old('valid_until', $customer->valid_until ?? '')" />
                <x-customers.inputs.customer-day :value="old('customer_day', $customer->customer_day ?? '')" />
                <x-customers.inputs.customer-day-preferred :value="old('customer_day_preferred', $customer->customer_day_preferred ?? '')" />
                <x-customers.inputs.income :value="old('income', $customer->income ?? '')" />
                <x-customers.inputs.customer-category :value="old('customer_category', $customer->customer_category ?? '')" />
            </div>
        </section>

        {{-- Buttons --}}
        <div class="flex justify-end gap-3 pt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-md shadow">
                Speichern
            </button>
            <a href="{{ url()->previous() }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-gray-200 rounded-md shadow">
                Abbrechen
            </a>
        </div>
    </form>
</div>
