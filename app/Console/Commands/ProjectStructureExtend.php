<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectStructureExtend extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'project:structure-extend {--dry-run : Show actions without writing}';

    /**
     * The console command description.
     */
    protected $description = 'Extend Laravel project structure with recommended directories and stubs, logging all actions.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $base   = base_path();
        $envProj = env('PROJ_NAME');
        $project = $envProj ?: basename($base);
        $logPath = "$base/.logs/project/{$project}-struct-extend.json";

        $dirs = [
            'app/Actions',
            'app/DTOs',
            'app/Services',
            'app/ViewModels',
            'resources/views/layouts',
            'resources/views/users',
            'resources/views/dashboard',
            'database/seeders',
            'tests/Feature',
            'tests/Unit',
        ];

        $files = [
            'resources/views/layouts/app.blade.php' => <<<BLADE
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>@yield('title', '$project')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
<header class="p-4 bg-gray-200"><h1 class="text-xl">$project</h1></header>
<main class="p-6">@yield('content')</main>
</body>
</html>
BLADE,
        ];

        $created = 0;
        $skipped = 0;
        $entries = [];

        foreach ($dirs as $rel) {
            $path = "$base/$rel";
            if (File::exists($path)) {
                $this->line("skip: $rel");
                $entries[] = [
                    'timestamp' => now()->toIso8601String(),
                    'type'      => 'dir',
                    'path'      => $rel,
                    'result'    => 'skipped',
                    'message'   => 'Already exists',
                ];
                $skipped++;
                continue;
            }
            $entries[] = [
                'timestamp' => now()->toIso8601String(),
                'type'      => 'dir',
                'path'      => $rel,
                'result'    => $dryRun ? 'planned' : 'created',
                'message'   => $dryRun ? 'Would be created' : 'Directory created',
            ];
            $dryRun ?: File::makeDirectory($path, 0755, true);
            $this->info(($dryRun ? 'plan' : 'make') . ": $rel");
            $created++;
        }

        foreach ($files as $rel => $content) {
            $path = "$base/$rel";
            if (File::exists($path)) {
                $this->line("skip: $rel");
                $entries[] = [
                    'timestamp' => now()->toIso8601String(),
                    'type'      => 'file',
                    'path'      => $rel,
                    'result'    => 'skipped',
                    'message'   => 'Already exists',
                ];
                $skipped++;
                continue;
            }
            $entries[] = [
                'timestamp' => now()->toIso8601String(),
                'type'      => 'file',
                'path'      => $rel,
                'result'    => $dryRun ? 'planned' : 'created',
                'message'   => $dryRun ? 'Would be written' : 'File created',
            ];
            if (!$dryRun) {
                File::ensureDirectoryExists(dirname($path));
                File::put($path, $content);
            }
            $this->info(($dryRun ? 'plan' : 'make') . ": $rel");
            $created++;
        }

        if (!$dryRun) {
            File::ensureDirectoryExists(dirname($logPath));
            File::append($logPath, json_encode($entries, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . PHP_EOL);
        }

        $this->newLine();
        $this->line("Project: $project");
        $this->line("Dry run: " . ($dryRun ? 'yes' : 'no'));
        $this->line("Created/planned: $created  |  Skipped: $skipped");
        $this->line("Log file: $logPath");

        return Command::SUCCESS;
    }
}
