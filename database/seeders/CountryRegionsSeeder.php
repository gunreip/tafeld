<?php

// tafeld/database/seeders/CountryRegionsSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CountryRegion;
use App\Models\Country;
use Illuminate\Support\Str;

class CountryRegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Deutschland-ID holen
        $de = Country::where('iso_3166_2', 'DE')->firstOrFail();
        $countryId = $de->id;

        // Bundesländer (16)
        $regions = [
            ['BW', 'Baden-Württemberg',      'BADEN-WUERTTEMBERG'],
            ['BY', 'Bayern',                 'BAYERN'],
            ['BE', 'Berlin',                 'BERLIN'],
            ['BB', 'Brandenburg',            'BRANDENBURG'],
            ['HB', 'Bremen',                 'BREMEN'],
            ['HH', 'Hamburg',                'HAMBURG'],
            ['HE', 'Hessen',                 'HESSEN'],
            ['MV', 'Mecklenburg-Vorpommern', 'MECKLENBURG-VORPOMMERN'],
            ['NI', 'Niedersachsen',          'NIEDERSACHSEN'],
            ['NW', 'Nordrhein-Westfalen',    'NORDRHEIN-WESTFALEN'],
            ['RP', 'Rheinland-Pfalz',        'RHEINLAND-PFALZ'],
            ['SL', 'Saarland',               'SAARLAND'],
            ['SN', 'Sachsen',                'SACHSEN'],
            ['ST', 'Sachsen-Anhalt',         'SACHSEN-ANHALT'],
            ['SH', 'Schleswig-Holstein',     'SCHLESWIG-HOLSTEIN'],
            ['TH', 'Thüringen',              'THUERINGEN'],
        ];

        foreach ($regions as [$iso2, $name, $iso3]) {
            CountryRegion::firstOrCreate(
                [
                    'country_id'    => $countryId,
                    'iso_3166_2'    => 'DE-' . $iso2,
                ],
                [
                    'id'              => Str::ulid()->toBase32(),
                    'iso_3166_3'      => 'DE-' . $iso3,
                    'name_de'         => $name,
                    'name_en'         => $name,
                    'fullname_de'     => $name,
                    'fullname_en'     => $name,

                    'translit_de'     => Str::of($name)->ascii()->value(),
                    'translit_en'     => Str::of($name)->ascii()->value(),

                    'sort_key_de'     => Str::of($name)->ascii()->lower()->value(),
                    'sort_key_en'     => Str::of($name)->ascii()->lower()->value(),
                ]
            );
        }
    }
}
