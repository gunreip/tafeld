<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProjectDatabase extends Command
{
    protected $signature = 'project:database {--table=} {--limit=5} {--where=}';
    protected $description = 'Export specified table data to storage/app/tables/{table}.json (nested under table name)';

    public function handle(): int
    {
        $table = $this->option('table');
        $limit = (int) $this->option('limit');
        $where = $this->option('where');

        if (empty($table)) {
            $this->error('Missing required option: --table');
            return self::FAILURE;
        }

        if (!DB::getSchemaBuilder()->hasTable($table)) {
            $this->error("Table '{$table}' does not exist.");
            return self::FAILURE;
        }

        $query = DB::table($table);

        if ($where) {
            [$column, $value] = explode(':', $where, 2) + [null, null];
            if ($column && $value) {
                $query->where($column, $value);
            }
        }

        $records = $query->limit($limit)->get();

        $export = [
            $table => $records,
        ];

        $dir = storage_path('app/tables');
        File::ensureDirectoryExists($dir);

        $path = "{$dir}/{$table}.json";
        File::put($path, json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        $this->info("Exported {$records->count()} record(s) from '{$table}' → {$path}");

        return self::SUCCESS;
    }
}
