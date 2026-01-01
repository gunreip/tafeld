<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ProjectDataset extends Command
{
    protected $signature = 'project:dataset
                            {table : Name der Datenbanktabelle, z.B. people}
                            {--limit=10 : Anzahl der auszulesenden Datensätze}';

    protected $description = 'Erzeugt Schema- und Datensatz-Audits einer Tabelle in .audits/database/ als JSON.';

    public function handle()
    {
        $table = $this->argument('table');
        $limit = (int)$this->option('limit');

        $this->info("Starte Datenbank-Audit für Tabelle: {$table}");

        if (!Schema::hasTable($table)) {
            $this->error("Die Tabelle '{$table}' existiert nicht.");
            return Command::FAILURE;
        }

        $auditDir = base_path('.audits/database');
        File::ensureDirectoryExists($auditDir);

        /*
        |--------------------------------------------------------------------------
        | SCHEMA-AUDIT
        |--------------------------------------------------------------------------
        */
        $schemaFile = "{$auditDir}/schema-{$table}.json";

        // Nutzt PostgreSQL-kompatible Abfrage für Column-Metadaten
        $columns = DB::select("
            SELECT column_name, data_type, is_nullable, column_default
            FROM information_schema.columns
            WHERE table_name = ?
            ORDER BY ordinal_position
        ", [$table]);

        $schemaJson = json_encode([
            'table'        => $table,
            'generated_at' => now()->toDateTimeString(),
            'columns'      => $columns,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        File::put($schemaFile, $schemaJson);

        $this->info("Schema-Audit geschrieben:");
        $this->line("  {$schemaFile}");

        /*
        |--------------------------------------------------------------------------
        | DATASET-AUDIT
        |--------------------------------------------------------------------------
        */
        $datasetFile = "{$auditDir}/dataset-{$table}.json";

        $data = DB::table($table)->limit($limit)->get();

        $datasetJson = json_encode([
            'table'  => $table,
            'limit'  => $limit,
            'count'  => $data->count(),
            'data'   => $data,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        File::put($datasetFile, $datasetJson);

        $this->info("Dataset-Audit geschrieben:");
        $this->line("  {$datasetFile}");

        $this->info("Datenbank-Audit erfolgreich abgeschlossen.");

        return Command::SUCCESS;
    }
}
