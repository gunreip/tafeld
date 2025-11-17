<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectBreezeKill extends Command
{
    protected $signature = 'project:breeze-kill';
    protected $description = 'Scans all Blade views for <x-*> components (Breeze / setup) and writes full audits to JSON & HTML.';

    public function handle()
    {
        $this->info('Scanning Blade views for Breeze/Livewire component usage…');

        $viewRoot = resource_path('views');

        // 1) Alle Blade-Dateien sammeln
        $bladeFiles = collect(File::allFiles($viewRoot))
            ->filter(fn($f) => str_ends_with($f->getFilename(), '.blade.php'))
            ->values();

        // Suchmuster: ALLE <x-*> Komponenten
        $componentPattern = '/<\s*x-([a-zA-Z0-9\-\_\.]+)(\s|>)/';

        $components = [];   // Abschnittweise
        $files = [];        // Dateiweise

        foreach ($bladeFiles as $file) {
            $path = $file->getRealPath();
            $content = File::get($path);
            $lines = explode("\n", $content);

            $fileMatches = [];
            $classification = 'neutral';

            foreach ($lines as $lineNumber => $line) {
                if (preg_match_all($componentPattern, $line, $matches)) {

                    foreach ($matches[1] as $idx => $componentName) {
                        $matchedTag = $matches[0][$idx] ?? $matches[0][0] ?? '';

                        // 1) Abschnittweise sammeln
                        $components[$componentName][] = [
                            'file' => $path,
                            'line' => $lineNumber + 1,
                            'code' => $line,
                        ];

                        // 2) Dateiweise sammeln
                        $fileMatches[] = [
                            'line'      => $lineNumber + 1,
                            'code'      => $line,
                            'component' => $componentName,
                        ];

                        // Klassifizierung anwenden (erstes Vorkommen entscheidet)
                        if ($classification === 'neutral') {
                            if ($this->isBreezeComponent($componentName)) {
                                $classification = 'Breeze';
                            } else {
                                $classification = 'Livewire';
                            }
                        }
                    }
                }
            }

            $files[] = [
                'file'           => $path,
                'classification' => $classification,
                'matches'        => $fileMatches,
            ];
        }

        // Zähler pro Klassifikation
        $breezeFiles  = collect($files)->where('classification', 'Breeze')->count();
        $livewireFiles = collect($files)->where('classification', 'Livewire')->count();
        $neutralFiles = collect($files)->where('classification', 'neutral')->count();
        $totalMatches = collect($files)->sum(fn($f) => count($f['matches']));

        //
        // --- Ausgabe-Verzeichnis vorbereiten ---
        //
        $auditDir = base_path('.audits/laravel/breeze');
        if (!File::exists($auditDir)) {
            File::makeDirectory($auditDir, 0755, true);
        }

        $jsonPath = $auditDir . '/breeze-kill.json';
        $htmlPath = $auditDir . '/breeze-kill.html';

        //
        // --- JSON erzeugen ---
        //
        $jsonData = [
            'summary' => [
                'total_blade_files' => $bladeFiles->count(),
                'total_components'  => count($components),
                'total_matches'     => $totalMatches,
                'breeze_files'      => $breezeFiles,
                'livewire_files'    => $livewireFiles,
                'neutral_files'     => $neutralFiles,
                'generated_at'      => now()->toDateTimeString(),
            ],
            'components' => $components,
            'files'      => $files,
        ];

        File::put($jsonPath, json_encode($jsonData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        //
        // --- HTML-Report erzeugen ---
        //
        $html = "<html><head>
            <meta charset='utf-8'>
            <title>Breeze-Kill Audit</title>
            <style>
                body { font-family: monospace; background: #111; color: #eee; padding: 20px; }
                h1 { color: #9cf; }
                h2 { color: #fc9; margin-top: 30px; }
                details { margin-bottom: 20px; }
                summary { cursor: pointer; font-size: 16px; margin-bottom: 8px; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                td, th { border: 1px solid #555; padding: 6px; vertical-align: top; }
                th { background: #333; color: #fff; }
                tr:nth-child(even) { background: #1a1a1a; }
                code { color: #afa; }
                .badge { padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: 600; }
                .badge-breeze { background: #5b21b6; color: #fff; }
                .badge-livewire { background: #2563eb; color: #fff; }
                .badge-neutral { background: #4b5563; color: #e5e7eb; }
            </style>
        </head><body>";

        $html .= "<h1>Breeze-Kill Audit</h1>";

        // Summary als Tabelle
        $s = $jsonData['summary'];
        $html .= "<h2>Summary</h2>";
        $html .= "<table>
            <tr><th>Key</th><th>Value</th></tr>
            <tr><td>Total Blade Files</td><td>{$s['total_blade_files']}</td></tr>
            <tr><td>Total Components</td><td>{$s['total_components']}</td></tr>
            <tr><td>Total Matches</td><td>{$s['total_matches']}</td></tr>
            <tr><td>Breeze Files</td><td>{$s['breeze_files']}</td></tr>
            <tr><td>Livewire Files</td><td>{$s['livewire_files']}</td></tr>
            <tr><td>Neutral Files</td><td>{$s['neutral_files']}</td></tr>
            <tr><td>Generated At</td><td>{$s['generated_at']}</td></tr>
        </table>";

        // Abschnittweise (pro Komponente)
        $html .= "<h2>Breeze / Livewire Component Usage (by component)</h2>";
        foreach ($components as $component => $matches) {
            $html .= "<details><summary>&lt;x-$component&gt; (" . count($matches) . ")</summary>";
            $html .= "<table><tr><th>File</th><th>Line</th><th>Code</th></tr>";
            foreach ($matches as $m) {
                $html .= "<tr>
                            <td>" . htmlspecialchars($m['file']) . "</td>
                            <td>{$m['line']}</td>
                            <td><code>" . htmlspecialchars($m['code']) . "</code></td>
                          </tr>";
            }
            $html .= "</table></details>";
        }

        // Dateiweise (mit Badges)
        $html .= "<h2>File Overview (by file)</h2>";
        foreach ($files as $f) {
            $class = $f['classification'];
            $badgeClass = match ($class) {
                'Breeze'   => 'badge-breeze',
                'Livewire' => 'badge-livewire',
                default    => 'badge-neutral',
            };
            $badgeHtml = "<span class='badge {$badgeClass}'>{$class}</span>";
            $html .= "<details><summary>" . htmlspecialchars($f['file']) . " — {$badgeHtml} (" . count($f['matches']) . ")</summary>";
            $html .= "<table><tr><th>Line</th><th>Component</th><th>Code</th></tr>";
            foreach ($f['matches'] as $m) {
                $html .= "<tr>
                            <td>{$m['line']}</td>
                            <td>" . htmlspecialchars($m['component']) . "</td>
                            <td><code>" . htmlspecialchars($m['code']) . "</code></td>
                          </tr>";
            }
            $html .= "</table></details>";
        }

        $html .= "</body></html>";

        File::put($htmlPath, $html);

        // Nur die Audit-Pfade in die Konsole
        $this->info("JSON Audit: $jsonPath");
        $this->info("HTML Audit: $htmlPath");

        return self::SUCCESS;
    }

    /**
     * Klassifizierung: erkenne typische Breeze-Komponenten
     */
    private function isBreezeComponent(string $componentName): bool
    {
        $breezePrefixes = [
            'app-layout',
            'guest-layout',
            'auth-card',
            'auth-session-status',
            'authentication-card',
            'validation-errors',
            'dropdown',
            'dropdown-link',
            'button',
            'input',
            'label',
            'checkbox',
            'application-logo',
            'nav-link',
        ];

        foreach ($breezePrefixes as $prefix) {
            if (str_starts_with($componentName, $prefix)) {
                return true;
            }
        }

        return false;
    }
}
