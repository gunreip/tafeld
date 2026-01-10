<?php

// tafeld/app/Console/Commands/ProjectVersions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class ProjectVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit: Installed Composer + npm package versions';

    /**
     * Compare installed version with latest version and return update type:
     * major / minor / patch / none
     */
    protected function determineUpdateType(string $current, string $latest): string
    {
        $c = explode('.', preg_replace('/[^0-9.]/', '', $current));
        $l = explode('.', preg_replace('/[^0-9.]/', '', $latest));

        $c = array_pad($c, 3, 0);
        $l = array_pad($l, 3, 0);

        if ($l[0] > $c[0]) {
            return 'major';
        }
        if ($l[0] === $c[0] && $l[1] > $c[1]) {
            return 'minor';
        }
        if ($l[0] === $c[0] && $l[1] === $c[1] && $l[2] > $c[2]) {
            return 'patch';
        }

        return 'none';
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // ---------------------------------------------------------------------
        // 1) Composer-Daten laden
        // ---------------------------------------------------------------------
        $composerLock = base_path('composer.lock');
        $composer = File::exists($composerLock)
            ? json_decode(File::get($composerLock), true)
            : ['packages' => []];

        $composerPackages = $composer['packages'] ?? [];

        // Neueste Composer-Versionen
        $composerLatestRaw = shell_exec('composer outdated --format=json');
        $composerLatest = [];

        if ($composerLatestRaw) {
            $decoded = json_decode($composerLatestRaw, true);
            foreach ($decoded['installed'] ?? [] as $item) {
                $composerLatest[$item['name']] = $item['latest'];
            }
        }

        // Composer alphabetisch sortieren
        usort($composerPackages, fn($a, $b) => strcmp($a['name'], $b['name']));

        // Composer update-info ergänzen + Dependency-Chain
        foreach ($composerPackages as &$pkg) {
            $name = $pkg['name'];
            $current = $pkg['version'];

            if (isset($composerLatest[$name])) {
                $latest = $composerLatest[$name];
                $pkg['version_latest'] = $latest;
                $pkg['update_type'] = $this->determineUpdateType($current, $latest);
            } else {
                $pkg['version_latest'] = $current;
                $pkg['update_type'] = 'none';
            }

            // ---------------------------------------------
            // Dependency-Chain (Composer) – direct only
            // ---------------------------------------------
            $requires = $pkg['require'] ?? [];
            $deps = [];

            foreach ($requires as $depName => $depVersion) {
                // passender Eintrag im composer.lock suchen
                $lockEntry = collect($composerPackages)
                    ->firstWhere('name', $depName);

                if ($lockEntry) {
                    $sub = $lockEntry['require'] ?? [];
                    $deps[] = [
                        'name'      => $depName,
                        'version'   => $lockEntry['version'] ?? '',
                        'sub_count' => max(0, count($sub)),
                    ];
                }
            }

            $pkg['deps'] = $deps;
        }
        unset($pkg);

        // ---------------------------------------------------------------------
        // 2) Node-Daten laden
        // ---------------------------------------------------------------------
        $packageLock = base_path('package-lock.json');
        $node = File::exists($packageLock)
            ? json_decode(File::get($packageLock), true)
            : ['packages' => []];

        // Dependencies + devDependencies (Top-Level)
        $packageJson = json_decode(File::get(base_path('package.json')), true);

        $deps = array_keys($packageJson['dependencies'] ?? []);
        $devDeps = array_keys($packageJson['devDependencies'] ?? []);
        $topLevel = array_unique(array_merge($deps, $devDeps));

        // Neueste npm-Versionen
        $npmOutdatedRaw = shell_exec('npm outdated --json 2>/dev/null');
        $npmLatest = $npmOutdatedRaw ? json_decode($npmOutdatedRaw, true) : [];

        // Top-Level npm packages extrahieren + sortieren + update info + Dependency-Chain
        $nodePackages = collect($node['packages'] ?? [])
            ->filter(function ($data, $key) use ($topLevel) {
                $clean = basename($key);
                return in_array($clean, $topLevel);
            })
            ->map(function ($data, $key) use ($npmLatest, $node) {
                $name = basename($key);
                $current = $data['version'] ?? null;

                if (isset($npmLatest[$name])) {
                    $latest = $npmLatest[$name]['latest'] ?? $current;
                    $updateType = $this->determineUpdateType($current, $latest);
                } else {
                    $latest = $current;
                    $updateType = 'none';
                }

                // ---------------------------------------------
                // Dependency-Chain (npm) – direct only
                // ---------------------------------------------
                $requires = $data['dependencies'] ?? [];
                $deps = [];

                foreach ($requires as $depName => $depSpec) {
                    // Key in package-lock suchen (z. B. 'node_modules/postcss')
                    $path = "node_modules/$depName";

                    if (isset($node['packages'][$path])) {
                        $entry = $node['packages'][$path];
                        $sub = $entry['dependencies'] ?? [];

                        $deps[] = [
                            'name'      => $depName,
                            'version'   => $entry['version'] ?? '',
                            'sub_count' => max(0, count($sub)),
                        ];
                    }
                }

                return [
                    'name'          => $name,
                    'version'       => $current,
                    'version_latest' => $latest,
                    'update_type'   => $updateType,
                    'deps'          => $deps,
                ];
            })
            ->sortBy('name')
            ->values()
            ->all();

        // ---------------------------------------------------------------------
        // 3) Summary-Daten
        // ---------------------------------------------------------------------
        $summary = [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'node' => trim(shell_exec('node -v') ?? ''),
            'npm' => trim(shell_exec('npm -v') ?? ''),
            'composer_count' => count($composerPackages),
            'npm_count' => count($nodePackages),
        ];

        // ---------------------------------------------------------------------
        // 4) Ausgabe-Verzeichnis erzeugen
        // ---------------------------------------------------------------------
        $dir = base_path('.audits/versions');
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0775, true);
        }

        // Mit Timestamp
        // $ts = now()->format('Y-m-d_H-i-s');
        // $jsonPath = $dir . "/laravel-packages_{$ts}.json";
        // $htmlPath = $dir . "/laravel-packages_{$ts}.html";

        // ohne Timestamp
        $jsonPath = $dir . "/laravel-packages.json";
        $htmlPath = $dir . "/laravel-packages.html";

        // ---------------------------------------------------------------------
        // 5) JSON-Datei schreiben
        // ---------------------------------------------------------------------
        $jsonData = [
            'summary' => $summary,
            'system' => [
                'postgres' => [
                    'client_version' => trim(
                        shell_exec('/usr/bin/psql --version 2>/dev/null') ?? ''
                    ),
                    'server_version' => (function () {
                        try {
                            $row = DB::selectOne('select version() as v');
                            return $row->v ?? '';
                        } catch (\Throwable $e) {
                            return '';
                        }
                    })(),
                    'ready' => trim(
                        shell_exec('/usr/bin/pg_isready 2>/dev/null') ?? ''
                    ),
                ],
                'nginx' => [
                    'version' => trim(
                        shell_exec('/usr/sbin/nginx -v 2>&1') ?? ''
                    ),
                ],
                'domains' => [
                    'tafeld.test' => trim(
                        shell_exec('/usr/bin/getent hosts tafeld.test | awk \'{print $1}\'') ?? ''
                    ),
                ],
            ],
            'composer' => $composerPackages,
            'npm' => $nodePackages,
        ];

        File::put($jsonPath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        // ---------------------------------------------------------------------
        // 6) HTML schreiben
        // ---------------------------------------------------------------------
        $html = view('audits.versions-template', [
            'summary' => $summary,
            'composer' => $composerPackages,
            'npm' => $nodePackages,
        ])->render();

        File::put($htmlPath, $html);

        // ---------------------------------------------------------------------
        // 7) Console-Ausgabe
        // ---------------------------------------------------------------------
        $this->info("Audit erstellt:");
        $this->info(" - " . str_replace(base_path() . '/', '', $htmlPath));
        $this->info(" - " . str_replace(base_path() . '/', '', $jsonPath));
    }
}
