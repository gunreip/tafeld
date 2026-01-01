<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    // Model-Events sind aktiv; ULID-Hooks funktionieren jetzt korrekt
    // use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class, // nur wenn vorhanden
            CountriesSeeder::class,
            AdminSeeder::class,
            CountriesSortKeyFixSeeder::class,
            CountryRegionsSeeder::class,
            PeopleSeeder::class,
            PeopleSortKeyFixSeeder::class,
            HolidaysSeeder::class,
            EventSeeder::class,
            CountryWorkAreaSeeder::class,
            DebugConfigSeeder::class,
            UiPreferenceDefaultsSeeder::class,
            AppSettingsSeeder::class,
        ]);
    }
}
