<?php

// tafeld/app/Services/Events/Parsers/IcsParser.php

namespace App\Services\Events\Parsers;

use Illuminate\Support\Str;
use App\Enums\EventType;

class IcsParser
{
    /**
     * Parse an ICS file and return normalized event entries.
     *
     * @param  string  $path
     * @return array
     */
    public static function parse(string $path): array
    {
        if (! file_exists($path)) {
            return [];
        }

        $content = file_get_contents($path);
        if (! $content) {
            return [];
        }

        $lines = preg_split('/\R/', $content);

        $entries = [];
        $current = [];

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === 'BEGIN:VEVENT') {
                $current = [];
                continue;
            }

            if ($line === 'END:VEVENT') {
                if (isset($current['summary'], $current['start'], $current['end'])) {
                    $entries[] = self::normalizeEvent($current);
                }
                $current = [];
                continue;
            }

            if (str_starts_with($line, 'SUMMARY')) {
                $parts = explode(':', $line, 2);
                $current['summary'] = trim($parts[1] ?? '');
            }

            if (str_starts_with($line, 'DTSTART')) {
                $value = explode(':', $line)[1] ?? null;
                if ($value && strlen($value) >= 8) {
                    $current['start'] =
                        substr($value, 0, 4) . '-' .
                        substr($value, 4, 2) . '-' .
                        substr($value, 6, 2);
                }
            }

            if (str_starts_with($line, 'DTEND')) {
                $value = explode(':', $line)[1] ?? null;
                if ($value && strlen($value) >= 8) {
                    $current['end'] =
                        substr($value, 0, 4) . '-' .
                        substr($value, 4, 2) . '-' .
                        substr($value, 6, 2);
                }
            }
        }

        return $entries;
    }

    /**
     * Normalize a single VEVENT block into an Event dataset.
     */
    protected static function normalizeEvent(array $event): array
    {
        $summary = $event['summary'];

        $lower = Str::lower($summary);
        if (str_contains($lower, 'company') || str_contains($lower, 'betrieb')) {
            $type = EventType::Business;
        } elseif (str_contains($lower, 'ferien') || str_contains($lower, 'holiday')) {
            $type = EventType::School;
        } else {
            $type = EventType::Other;
        }

        $nameDe = $summary;
        $nameEn = $summary;

        return [
            'name_de'      => $nameDe,
            'name_en'      => $nameEn,
            'translit_de'  => Str::of($nameDe)->ascii()->lower(),
            'translit_en'  => Str::of($nameEn)->ascii()->lower(),
            'sort_key_de'  => Str::of($nameDe)->ascii()->lower(),
            'sort_key_en'  => Str::of($nameEn)->ascii()->lower(),
            'start_date'   => $event['start'],
            'end_date'     => $event['end'],
            'event_type'   => $type,
        ];
    }
}
