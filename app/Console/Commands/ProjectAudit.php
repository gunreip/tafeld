<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

class ProjectAudit extends Command
{
    protected $signature = 'project:audit {--format=md} {--with-project-structure}';
    protected $description = 'System-, Datenbank- und Projekt-Audit mit Versionsabfragen.';

    public function handle(): int
    {
        $project = env('PROJ_NAME', basename(base_path()));
        $format = strtolower($this->option('format'));
        $targetDir = base_path('.logs/audits');
        File::ensureDirectoryExists($targetDir);

        $report = [
            'project'   => $project,
            'timestamp' => now()->toDateTimeString(),
            'env'       => App::environment(),
            'laravel'   => App::version(),
            'php'       => phpversion(),
            'timezone'  => Config::get('app.timezone'),
            'paths'     => [
                'base' => base_path(),
                'storage' => storage_path(),
                'public' => public_path(),
            ],
        ];

        $report['system'] = $this->collectSystemInfo();
        $report['databases'] = $this->collectDatabaseInfo();
        $report['webservers'] = $this->collectWebServers();
        $report['composer_packages'] = $this->detectComposerPackages();
        $report['node_packages'] = $this->detectNodePackages();

        $structure = null;
        if ($this->option('with-project-structure')) {
            $this->info('→ Generiere Projektstruktur …');
            Artisan::call('project:structure', ['--format' => 'txt']);
            $path = base_path(".logs/structure/{$project}-struct.txt");
            $structure = File::exists($path) ? File::get($path) : '(keine Struktur gefunden)';
        }

        $ext = $format;
        $output = $this->render($report, $structure, $format);
        $auditPath = "{$targetDir}/{$project}-audit.{$ext}";
        File::put($auditPath, $output);
        $this->info("Audit gespeichert: {$auditPath}");

        return Command::SUCCESS;
    }

    protected function shell(string $cmd): string
    {
        $res = @shell_exec($cmd . ' 2>&1');
        return $res ? trim($res) : '(nicht installiert)';
    }

    protected function collectSystemInfo(): array
    {
        return [
            'os' => $this->shell('uname -a'),
            'os_release' => File::exists('/etc/os-release') ? trim(@shell_exec('cat /etc/os-release')) : null,
            'php' => $this->shell('php -v'),
            'node' => $this->shell('node -v'),
            'composer' => $this->shell('composer -V'),
        ];
    }

    protected function collectDatabaseInfo(): array
    {
        return [
            'PostgreSQL' => $this->shell('psql --version'),
            'MySQL' => $this->shell('mysql --version'),
            'SQLite' => $this->shell('sqlite3 --version'),
            'Redis' => $this->shell('redis-server --version'),
            'MongoDB' => $this->shell('mongod --version'),
        ];
    }

    protected function collectWebServers(): array
    {
        return [
            'Apache' => $this->shell('apache2 -v'),
            'Nginx' => $this->shell('nginx -v'),
            'Caddy' => $this->shell('caddy version'),
        ];
    }

    protected function detectComposerPackages(): array
    {
        $file = base_path('composer.lock');
        if (!File::exists($file)) return [];
        $data = json_decode(File::get($file), true);
        $packages = collect($data['packages'] ?? [])->mapWithKeys(fn($p) => [$p['name'] => $p['version']]);
        return $packages->all();
    }

    protected function detectNodePackages(): array
    {
        $file = base_path('package.json');
        if (!File::exists($file)) return [];
        $data = json_decode(File::get($file), true);
        return [
            'dependencies' => $data['dependencies'] ?? [],
            'devDependencies' => $data['devDependencies'] ?? [],
        ];
    }

    protected function render(array $d, ?string $structure, string $format): string
    {
        if ($format === 'json') {
            if ($structure) $d['project_structure'] = explode("\n", $structure);
            return json_encode($d, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        }
        if ($format === 'html') return $this->renderHtml($d, $structure);
        return $this->renderMarkdown($d, $structure);
    }

    protected function renderMarkdown(array $d, ?string $structure): string
    {
        $out = "# Projekt-Audit: {$d['project']}\n\n";
        $out .= "## System\n```\n" . json_encode($d['system'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n";
        $out .= "## Datenbanken\n```\n" . json_encode($d['databases'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n";
        $out .= "## Webserver\n```\n" . json_encode($d['webservers'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n";
        $out .= "## Composer-Pakete\n```\n" . json_encode($d['composer_packages'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n";
        $out .= "## Node-Pakete\n```\n" . json_encode($d['node_packages'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n```\n";
        if ($structure) $out .= "## Projektstruktur\n```\n{$structure}\n```\n";
        return $out;
    }

    protected function renderHtml(array $d, ?string $structure): string
    {
        $j = fn($x) => htmlspecialchars(json_encode($x, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), ENT_QUOTES);
        $struct = $structure ? "<h3>Projektstruktur</h3><pre>{$structure}</pre>" : '';
        return "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>{$d['project']} Audit</title><style>body{background:#111;color:#ddd;font-family:monospace;padding:1rem;}pre{background:#222;padding:1rem;}h2{color:#9cf;}</style></head><body><h1>{$d['project']} – Audit</h1><h2>System</h2><pre>{$j($d['system'])}</pre><h2>Datenbanken</h2><pre>{$j($d['databases'])}</pre><h2>Webserver</h2><pre>{$j($d['webservers'])}</pre><h2>Composer</h2><pre>{$j($d['composer_packages'])}</pre><h2>Node</h2><pre>{$j($d['node_packages'])}</pre>{$struct}</body></html>";
    }
}
