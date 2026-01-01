<?php

// tafeld/app/Console/Commands/ProjectDatasetDecrypt.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\File;

class ProjectDatasetDecrypt extends Command
{
    /**
     * Signature:
     *   php artisan project:dataset:decrypt people
     */
    protected $signature = 'project:dataset:decrypt 
        {table : Name der Datenbank-Tabelle}
        {--limit=50 : Max. Anzahl Datensätze}';

    protected $description = 'Entschlüsselt verschlüsselte Spalten einer Tabelle (people, countries, …) und erzeugt ein detailliertes JSON-Audit unter .audits/database/.';

    public function handle()
    {
        $table = $this->argument('table');
        $limit = (int)$this->option('limit');

        // Output-Verzeichnis
        $auditDir = base_path('.audits/database');
        if (!is_dir($auditDir)) {
            mkdir($auditDir, 0775, true);
        }

        $auditPath = $auditDir . "/decrypt-{$table}.json";

        // Prüfe, ob Tabelle existiert
        if (!DB::getSchemaBuilder()->hasTable($table)) {
            $this->error("Tabelle '{$table}' existiert nicht.");
            return Command::FAILURE;
        }

        $encryptedCols = $this->detectEncryptedColumns($table);

        // Ausgabe für encrypted columns
        if (empty($encryptedCols)) {
            $this->warn("Keine encrypted-Spalten in '{$table}' gefunden – Audit wird trotzdem erstellt.");
        } else {
            $this->info("Encrypted-Casts erkannt:");
            $this->info("  " . json_encode($encryptedCols));
        }

        $this->info("Starte Decrypt-Audit für '{$table}' …");
        $this->info("Verwendetes Limit: {$limit}");

        // Datensätze laden
        $rows = DB::table($table)->limit($limit)->get();

        $result = [];

        foreach ($rows as $row) {

            // Grundstruktur pro Datensatz
            $entry = [
                'id'     => $row->id ?? null,
                'record' => [],
            ];

            foreach ($encryptedCols as $col) {

                $raw = $row->{$col};

                if ($raw === null) {
                    $entry['record'][$col] = [
                        'encrypted_value' => null,
                        'decrypted_value' => null,
                        'status'          => 'null',
                    ];
                    continue;
                }

                // Decrypt
                try {
                    $decrypted = Crypt::decryptString($raw);

                    $entry['record'][$col] = [
                        'encrypted_value' => $raw,
                        'decrypted_value' => $decrypted,
                        'status'          => 'ok',
                    ];
                } catch (DecryptException $e) {
                    $entry['record'][$col] = [
                        'encrypted_value' => $raw,
                        'decrypted_value' => null,
                        'status'          => 'error',
                        'message'         => 'DecryptException (payload invalid)',
                    ];
                } catch (\Throwable $e) {
                    $entry['record'][$col] = [
                        'encrypted_value' => $raw,
                        'decrypted_value' => null,
                        'status'          => 'error',
                        'message'         => get_class($e) . ': ' . $e->getMessage(),
                    ];
                }
            }

            $result[] = $entry;
        }

        // JSON schreiben
        file_put_contents(
            $auditPath,
            json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->info("Decrypt-Audit gespeichert:");
        $this->info("→ {$auditPath}");

        return Command::SUCCESS;
    }


    /**
     * Encrypted Columns anhand Model-Casts ermitteln.
     */
    protected function detectEncryptedColumns(string $table): array
    {
        // Tabellen-Name → Model-Klasse
        $modelMap = [
            'people'    => \App\Models\Person::class,
            'countries' => \App\Models\Country::class,
            // weitere Modelle bei Bedarf ergänzen
        ];

        // Modell zuordnen
        if (! isset($modelMap[$table])) {
            $this->warn("Kein Model für '{$table}' registriert. Encrypted-Spalten können nicht automatisch erkannt werden.");
            return [];
        }

        $modelClass = $modelMap[$table];

        if (! class_exists($modelClass)) {
            $this->warn("Model-Klasse {$modelClass} existiert nicht.");
            return [];
        }

        /** @var \Illuminate\Database\Eloquent\Model $model */
        $model = app($modelClass);

        if (! method_exists($model, 'getCasts')) {
            $this->warn("Model für '{$table}' unterstützt getCasts() nicht.");
            return [];
        }

        $casts = $model->getCasts() ?? [];

        // Nur Casts vom Typ 'encrypted'
        $encryptedCols = collect($casts)
            ->filter(function ($type) {
                return $type === 'encrypted'
                    || str_starts_with($type, 'encrypted:')
                    || str_contains($type, 'encrypted');
            })
            ->keys()
            ->values()
            ->all();

        if (empty($encryptedCols)) {
            $this->warn("Im Model für '{$table}' wurden keine encrypted-Casts gefunden.");
        } else {
            $this->info("Verschlüsselte Spalten: " . implode(', ', $encryptedCols));
        }

        return $encryptedCols;
    }
}
