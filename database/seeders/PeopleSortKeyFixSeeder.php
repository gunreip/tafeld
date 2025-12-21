<?php

// tafeld/database/seeders/PeopleSortKeyFixSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PeopleSortKeyFixSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logPath = storage_path('logs/database/seeder-people_fix_sort_key.log');

        // LOG neu schreiben (nicht append!)
        File::ensureDirectoryExists(dirname($logPath));
        File::put($logPath, "=== PeopleSortKeyFixSeeder START: " . now() . " ===\n\n");

        $people = \App\Models\Person::all();

        File::append($logPath, "Gefundene Personen: " . $people->count() . "\n\n");

        foreach ($people as $person) {

            // ORIGINAL verschlüsselte Werte (RAW aus DB!)
            $encFirst = $person->getRawOriginal('first_name');
            $encLast  = $person->getRawOriginal('last_name');

            // ENTschlüsselt (Laravel-Cast übernimmt decrypt())
            $first = $person->first_name;
            $last  = $person->last_name;

            // sort_key + translit (nur wenn Klartext verfügbar)
            $firstSort  = $first ? Str::slug($first, '-') : null;
            $firstTrans = $first ? iconv('UTF-8', 'ASCII//TRANSLIT', $first) : null;

            $lastSort   = $last ? Str::slug($last, '-') : null;
            $lastTrans  = $last ? iconv('UTF-8', 'ASCII//TRANSLIT', $last) : null;

            // Speichern in DB
            $person->first_name_sort_key = $firstSort;
            $person->first_name_translit = $firstTrans;
            $person->last_name_sort_key  = $lastSort;
            $person->last_name_translit  = $lastTrans;

            $person->save();

            // ULTRA-DETAIL LOG
            File::append(
                $logPath,
                "Person {$person->id}:\n" .
                    "  DB ENCRYPTED RAW:\n" .
                    "    first_name (raw): {$encFirst}\n" .
                    "    last_name  (raw): {$encLast}\n\n" .
                    "  DECRYPTED (via cast):\n" .
                    "    first_name: {$first}\n" .
                    "    last_name : {$last}\n\n" .
                    "  COMPUTED:\n" .
                    "    first_name_sort_key: {$firstSort}\n" .
                    "    first_name_translit: {$firstTrans}\n" .
                    "    last_name_sort_key : {$lastSort}\n" .
                    "    last_name_translit : {$lastTrans}\n\n" .
                    "  UPDATED (saved to DB)\n" .
                    "-------------------------\n\n"
            );
        }

        File::append($logPath, "=== PeopleSortKeyFixSeeder END: " . now() . " ===\n");
    }
}
