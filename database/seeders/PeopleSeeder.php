<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Person;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('people')->truncate();

        // Alle Länder laden
        $countries = DB::table('countries')->get(['id', 'iso_3166_2']);
        if ($countries->isEmpty()) {
            throw new \RuntimeException('Countries table has no entries.');
        }

        // Mapping nach ISO
        $countryByIso = $countries->keyBy('iso_3166_2');

        // Beispiel-Datensätze
        $testPeople = [
            [
                'first_name' => 'John',
                'last_name'  => 'Doe',
                'email_local'  => 'john.doe',
                'email_domain' => 'example.com',
                'mobile' => ['US', '202', '5550199'],
                'phone'  => ['US', '202', '5550199'],
                'country_iso'     => 'US',
                'nationality_iso' => 'US',
            ],
            [
                'first_name' => 'Maria',
                'last_name'  => 'Gomez',
                'email_local'  => 'maria.gomez',
                'email_domain' => 'example.com',
                'mobile' => ['ES', '91', '123456'],
                'phone'  => ['ES', '91', '123456'],
                'country_iso'     => 'ES',
                'nationality_iso' => 'ES',
            ],
            [
                'first_name' => 'Michael',
                'last_name'  => 'Müller',
                'email_local'  => 'm.mueller',
                'email_domain' => 'example.com',
                'mobile' => ['DE', '30', '123456'],
                'phone'  => ['DE', '30', '123456'],
                'country_iso'     => 'DE',
                'nationality_iso' => 'DE',
            ],
        ];

        foreach ($testPeople as $p) {
            Person::create([
                'id' => Str::ulid()->toBase32(),

                'first_name'          => $p['first_name'],
                'last_name'           => $p['last_name'],
                'first_name_sort_key' => null,
                'first_name_translit' => null,
                'last_name_sort_key'  => null,
                'last_name_translit'  => null,

                'street'       => null,
                'house_number' => null,
                'country_id'   => optional($countryByIso[$p['country_iso']] ?? null)->id,
                'zipcode'      => null,
                'city'         => null,

                'nationality_id' => optional($countryByIso[$p['nationality_iso']] ?? null)->id,

                'birthdate'         => null,
                'employment_start'  => null,
                'employment_end'    => null,

                'mobile_country_id' => optional($countryByIso[$p['mobile'][0]] ?? null)->id,
                'mobile_area'       => $p['mobile'][1],
                'mobile_number'     => $p['mobile'][2],

                'phone_country_id' => optional($countryByIso[$p['phone'][0]] ?? null)->id,
                'phone_area'       => $p['phone'][1],
                'phone_number'     => $p['phone'][2],

                'email_local'  => $p['email_local'],
                'email_domain' => $p['email_domain'],
            ]);
        }
    }
}
