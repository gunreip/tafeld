<?php

// tafeld/app/Services/Events/EventImporter.php

namespace App\Services\Events;

use App\Models\Event;
use App\Models\CountryRegion;
use App\Enums\EventType;
use App\Services\Events\Parsers\JsonParser;
use App\Services\Events\Parsers\IcsParser;

class EventImporter
{
    /**
     * Import all events for the given list of event types.
     *
     * @param  array<string>|array<EventType>  $types
     * @return void
     */
    public static function importTypes(array $types): void
    {
        // Reset logfile at start (no append)
        file_put_contents(storage_path('logs/eventImporter.log'), "=== EventImporter START ===\n");

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "Importing types: " . json_encode($types) . "\n",
            FILE_APPEND
        );

        // Informative listing of known event types
        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "Available event types: school, business, holiday\n",
            FILE_APPEND
        );

        foreach ($types as $type) {
            $type = $type instanceof EventType ? $type->value : $type;
            self::importType($type);
        }
    }

    /**
     * Import all events of a single event type.
     */
    protected static function importType(string $type): void
    {
        $base = resource_path("data/events/{$type}");
        if (! is_dir($base)) {
            file_put_contents(
                storage_path('logs/eventImporter.log'),
                "[{$type}] BASE PATH NOT FOUND: {$base}\n",
                FILE_APPEND
            );
            return;
        }

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "[{$type}] BASE PATH OK: {$base}\n",
            FILE_APPEND
        );

        $dir = new \RecursiveDirectoryIterator($base);
        $it  = new \RecursiveIteratorIterator($dir);

        foreach ($it as $file) {
            if ($file->isDir()) {
                continue;
            }

            $path = $file->getPathname();
            $ext  = strtolower($file->getExtension());

            if (! in_array($ext, ['json', 'ics'], true)) {
                continue;
            }

            file_put_contents(
                storage_path('logs/eventImporter.log'),
                "[{$type}] FILE FOUND: {$path}\n",
                FILE_APPEND
            );

            // Additional debug for business events
            if ($type === 'business') {
                file_put_contents(
                    storage_path('logs/eventImporter.log'),
                    "[business] FILE CONFIRMED: {$path}\n",
                    FILE_APPEND
                );
            }

            self::importFile($type, $path);
        }
    }

    /**
     * Import a single JSON/ICS file.
     */
    protected static function importFile(string $type, string $path): void
    {
        $base = resource_path("data/events/{$type}" . DIRECTORY_SEPARATOR);
        $relative = str_replace($base, '', $path);

        // expected: <YEAR>/<REGION>.<ext>
        [$year, $filename] = explode(DIRECTORY_SEPARATOR, $relative);

        // -------------------------------
        // REGION-CODE GENERISCH ERMITTELN
        // -------------------------------
        $regionCode = pathinfo($filename, PATHINFO_FILENAME);  // z. B. "DE-NW" oder "NW"

        // Wenn Country nicht im Filename enthalten ist:
        if (! str_contains($regionCode, '-')) {
            // Country aus Ordner extrahieren, z. B.: .../events/DE/2025/NW.json
            $countryCode = basename(dirname(dirname($path)));   // "DE"
            $regionCode = "{$countryCode}-{$regionCode}";
        }

        // Lookup-Code ist jetzt generisch korrekt
        $lookupCode = $regionCode;

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "[{$type}] REGION CODE: file={$regionCode} lookup={$lookupCode}\n",
            FILE_APPEND
        );

        // Lookup im country_regions Table
        $region = CountryRegion::where('iso_3166_2', $lookupCode)->first();

        if (! $region) {
            file_put_contents(
                storage_path('logs/eventImporter.log'),
                "[{$type}] REGION NOT FOUND IN DB: {$lookupCode}\n",
                FILE_APPEND
            );
            return;
        }

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "[{$type}] REGION OK: DB-ID={$region->id}\n",
            FILE_APPEND
        );

        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($ext === 'json') {
            $entries = JsonParser::parse($path);
        } else {
            $entries = IcsParser::parse($path);
        }

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "[{$type}] PARSER entries=" . count($entries) . " file={$path}\n",
            FILE_APPEND
        );

        // Additional debug output for business events
        if ($type === 'business') {
            file_put_contents(
                storage_path('logs/eventImporter.log'),
                "[business] Parsed entries:\n" . json_encode($entries, JSON_PRETTY_PRINT) . "\n",
                FILE_APPEND
            );
        }

        foreach ($entries as $eventData) {
            self::storeEvent($region->id, $eventData, $type);
        }
    }

    /**
     * Persist a single normalized event.
     */
    protected static function storeEvent(string $regionId, array $data, string $type): void
    {
        // Business, School oder Holiday sauber erkennen
        $eventType = EventType::tryFrom($type);
        if (! $eventType) {
            return;
        }

        file_put_contents(
            storage_path('logs/eventImporter.log'),
            "[{$type}] STORE EVENT: region={$regionId}, type={$eventType->value}, name={$data['name_de']}, start={$data['start_date']}\n",
            FILE_APPEND
        );

        // Extra log payload for business events
        if ($type === 'business') {
            file_put_contents(
                storage_path('logs/eventImporter.log'),
                "[business] Final event payload:\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n",
                FILE_APPEND
            );
        }

        // Parser-Daten dürfen keine DB-Felder überschreiben
        unset($data['country_region_id'], $data['region'], $data['region_id'], $data['event_type']);

        Event::create(array_merge($data, [
            'country_region_id' => $regionId,
            'event_type'        => $eventType,
        ]));
    }
}
