<?php

// tafeld/app/Services/Events/Parsers/JsonParser.php

namespace App\Services\Events\Parsers;

use Illuminate\Support\Str;
use App\Enums\EventType;

class JsonParser
{
    /**
     * Parse a JSON file and return normalized event entries.
     *
     * @param  string  $path
     * @return array
     */
    public static function parse(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }

        $data = json_decode(file_get_contents($path), true);

        if (! is_array($data)) {
            return [];
        }

        $entries = [];

        foreach ($data as $item) {
            if (
                ! isset(
                    $item['name_de'],
                    $item['name_en'],
                    $item['start_date'],
                    $item['end_date'],
                    $item['type']
                )
            ) {
                continue;
            }

            $eventType = EventType::tryFrom($item['type']) ?? EventType::Other;

            $nameDe = $item['name_de'];
            $nameEn = $item['name_en'];

            $entries[] = [
                'name_de'      => $nameDe,
                'name_en'      => $nameEn,
                'translit_de'  => Str::of($nameDe)->ascii()->lower(),
                'translit_en'  => Str::of($nameEn)->ascii()->lower(),
                'sort_key_de'  => Str::of($nameDe)->ascii()->lower(),
                'sort_key_en'  => Str::of($nameEn)->ascii()->lower(),
                'start_date'   => $item['start_date'],
                'end_date'     => $item['end_date'],
                'event_type'   => $eventType,
                'description'  => $item['description'] ?? null,
            ];
        }

        return $entries;
    }
}
