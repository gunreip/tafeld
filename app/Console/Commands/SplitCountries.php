<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SplitCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:split';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Split complete country dataset into 7 JSON sets (50 records each)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = resource_path('data/countries/complete.json');
        $targetDir = resource_path('data/countries/sets');

        if (!file_exists($source)) {
            $this->error("Source file not found: $source");
            return Command::FAILURE;
        }

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // JSON laden
        $data = json_decode(file_get_contents($source), true);
        if (!is_array($data)) {
            $this->error('Invalid JSON in complete.json');
            return Command::FAILURE;
        }

        // Alphabetisch sortieren
        usort($data, fn($a, $b) => strcmp($a['iso_3166_2'], $b['iso_3166_2']));

        // In Gruppen à 50 splitten
        $chunks = array_chunk($data, 50);

        // Dateien schreiben
        $index = 1;
        foreach ($chunks as $chunk) {
            $filename = sprintf('%s/set-0%d.json', $targetDir, $index);
            file_put_contents(
                $filename,
                json_encode($chunk, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            $this->info("Created: set-0{$index}.json");
            $index++;
        }

        // Leere Sets auffüllen bis 7
        while ($index <= 7) {
            $filename = sprintf('%s/set-0%d.json', $targetDir, $index);
            file_put_contents($filename, "[]");
            $this->info("Created: set-0{$index}.json (empty)");
            $index++;
        }

        $this->info('Done.');
        return Command::SUCCESS;
    }
}
