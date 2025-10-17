<x-dashboard-layout title="Dashboard">
    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        <div class="card bg-base-200 shadow p-6">
            <h2 class="font-semibold mb-2 flex items-center gap-2">
                <x-heroicon-o-user-group class="w-5 h-5 text-primary" /> Personal
            </h2>
            <p class="text-sm text-base-content/70">Erfassung, Planung und Verwaltung.</p>
        </div>

        <div class="card bg-base-200 shadow p-6">
            <h2 class="font-semibold mb-2 flex items-center gap-2">
                <x-heroicon-o-briefcase class="w-5 h-5 text-primary" /> Kunden
            </h2>
            <p class="text-sm text-base-content/70">Kundenstammdaten und Historie.</p>
        </div>

        <div class="card bg-base-200 shadow p-6">
            <h2 class="font-semibold mb-2 flex items-center gap-2">
                <x-heroicon-o-banknotes class="w-5 h-5 text-primary" /> Finanzen
            </h2>
            <p class="text-sm text-base-content/70">Kassenbuch und Buchungen.</p>
        </div>
    </div>
</x-dashboard-layout>
