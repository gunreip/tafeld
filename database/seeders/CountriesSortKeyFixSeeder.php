<?php

// tafeld/database/seeders/CountriesSortKeyFixSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CountriesSortKeyFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logPath = storage_path('logs/database/seeder-countries_fix_sort_key.log');
        \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($logPath));
        \Illuminate\Support\Facades\File::put($logPath, "=== CountriesSortKeyFix START: " . now() . " ===\n\n");

        $countries = \App\Models\Country::all();

        \Illuminate\Support\Facades\File::append($logPath, "Gefundene Länder: " . $countries->count() . "\n\n");

        foreach ($countries as $country) {

            $before = [
                'sort_key_de' => $country->sort_key_de,
                'translit_de' => $country->translit_de,
            ];

            $sortKey = $country->name_de
                ? \Illuminate\Support\Str::slug($country->name_de, '-')
                : null;

            $translit = $country->name_de
                ? iconv('UTF-8', 'ASCII//TRANSLIT', $country->name_de)
                : null;

            $country->sort_key_de = $sortKey;
            $country->translit_de = $translit;
            $country->save();

            $after = [
                'sort_key_de' => $country->sort_key_de,
                'translit_de' => $country->translit_de,
            ];

            \Illuminate\Support\Facades\File::append(
                $logPath,
                "Country {$country->id}:\n" .
                    "  BEFORE: " . json_encode($before) . "\n" .
                    "  AFTER : " . json_encode($after) . "\n\n"
            );
        }

        \Illuminate\Support\Facades\File::append($logPath, "=== CountriesSortKeyFix END: " . now() . " ===\n");
    }
}
