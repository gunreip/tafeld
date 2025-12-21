<?php

// tafeld/database/seeders/CountryWorkAreaSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Enums\CountryWorkArea;

class CountryWorkAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // EU / EWR / Schweiz – Arbeitnehmerfreizügigkeit
        // ISO 3166-3 Codes
        $freeMovementCountries = [
            'AUT',
            'BEL',
            'BGR',
            'HRV',
            'CYP',
            'CZE',
            'DNK',
            'EST',
            'FIN',
            'FRA',
            'DEU',
            'GRC',
            'HUN',
            'IRL',
            'ITA',
            'LVA',
            'LTU',
            'LUX',
            'MLT',
            'NLD',
            'POL',
            'PRT',
            'ROU',
            'SVK',
            'SVN',
            'ESP',
            'SWE',

            // EWR (nicht EU)
            'ISL',
            'LIE',
            'NOR',

            // Schweiz
            'CHE',
        ];

        // „Privilegierte Staaten“ gemäß Vereinfachungsregelungen (§ 26 BeschV u. a.)
        // ISO 3166-3 Codes
        $privilegedCountries = [
            'AND', // Andorra
            'AUS', // Australien
            'ISR', // Israel
            'JPN', // Japan
            'CAN', // Kanada
            'KOR', // Republik Korea (Südkorea)
            'MCO', // Monaco
            'NZL', // Neuseeland
            'SMR', // San Marino
            'GBR', // Vereinigtes Königreich
            'USA', // Vereinigte Staaten von Amerika
        ];

        // 1) Standard: Drittstaat
        Country::query()->update([
            'work_area' => CountryWorkArea::THIRD_COUNTRY->value,
        ]);

        // 2) EU/EWR/Schweiz → volle Arbeitnehmerfreizügigkeit
        Country::whereIn('iso_3166_3', $freeMovementCountries)
            ->update([
                'work_area' => CountryWorkArea::EU_EEA_SWISS->value,
            ]);

        // 3) Privilegierte Staaten → vereinfachter Arbeitsmarktzugang
        Country::whereIn('iso_3166_3', $privilegedCountries)
            ->update([
                'work_area' => CountryWorkArea::PRIVILEGED->value,
            ]);
    }
}
