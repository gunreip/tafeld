<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Holiday;
use App\Models\Country;
use App\Support\Holidays\GermanyHolidayProvider;

class HolidaysSeeder extends Seeder
{
    public function run(): void
    {
        // DE-Land-ID holen
        $country = Country::where('iso_3166_2', 'DE')->firstOrFail();
        $countryId = $country->id;

        // Provider laden
        $provider = new GermanyHolidayProvider();

        // Zeitraum
        $startYear = 2000;
        $endYear   = now()->year + 10;

        for ($year = $startYear; $year <= $endYear; $year++) {

            // Feiertage über Provider generieren
            $holidays = $provider->generateForYear($year);

            foreach ($holidays as $h) {
                Holiday::create([
                    'country_id'         => $countryId,
                    'region_code'        => $h->region_code,
                    'date'               => $h->date,
                    'name_de'            => $h->name_de,
                    'translit_de'        => null, // optional, später befüllbar
                    'sort_key_de'        => Str::of($h->name_de)->ascii()->lower(),
                    'is_static'          => $h->is_static,
                    'is_business_closed' => true,
                    'display_date'       => $h->display_date,
                    'confession'         => $h->confession,
                ]);
            }
        }
    }
}
