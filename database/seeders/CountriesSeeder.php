<?php

// tafeld/database/seeders/CountriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('countries')->truncate();

        $setsPath = resource_path('data/countries/sets');

        foreach (glob($setsPath . '/set-0*.json') as $file) {
            $records = json_decode(file_get_contents($file), true);
            if (!is_array($records)) {
                continue;
            }

            foreach ($records as $c) {
                \App\Models\Country::create($c);
            }
        }
    }
}
