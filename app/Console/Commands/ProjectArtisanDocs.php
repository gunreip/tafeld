<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectArtisanDocs extends Command
{
    protected $signature = 'project:artisan-docs {--category=*} {--format=html}';
    protected $description = 'Generiert eine komplette HTML-Dokumentation aller Artisan-Commands (neu schreiben, kein Append).';

    public function handle(): int
    {
        // === Spinner starten ===============================================
        // $spinner = '/home/gunreip/code/_spinner-arrow.sh';
        // $spinner = '/home/gunreip/code/_spinner-braille.sh';
        // $spinner = '/home/gunreip/code/_spinner-circle.sh';
        $spinner = '/home/gunreip/code/_spinner-lines.sh';
        // $spinner = '/home/gunreip/code/_spinner-moon.sh';
        // $spinner = '/home/gunreip/code/_spinner-square.sh';
        $spinnerPid = null;
        if (is_file($spinner) && is_executable($spinner)) {
            // Schreibe die Ausgabe des Spinners direkt auf das Terminal device
            $tty = '/dev/tty';
            $cmd = "bash " . escapeshellarg($spinner) . " >" . escapeshellarg($tty) . " 2>" . escapeshellarg($tty) . " <" . escapeshellarg($tty) . " & echo $!";
            $spinnerPid = trim(shell_exec($cmd));
        }
        // ====================================================================

        $outDir = base_path('.logs/audits/artisan');
        File::ensureDirectoryExists($outDir);

        $cssPath = base_path('.logs/audits-artisan.css');
        if (!File::exists($cssPath)) {
            File::put($cssPath, $this->defaultCss());
        }

        $categoriesFilter = (array)$this->option('category');
        $commands = $this->collectCommands($categoriesFilter);

        $jsonl = $outDir . '/artisan-commands.jsonl';
        File::put($jsonl, '');
        foreach ($commands as $cat => $list) {
            foreach ($list as $entry) {
                File::append($jsonl, json_encode([
                    'category' => $cat,
                    'command' => $entry['name'],
                    'description' => $entry['description'],
                    'help_raw' => $entry['help'],
                ], JSON_UNESCAPED_SLASHES) . "\n");
            }
        }

        $html = $this->renderHtml($commands);
        $htmlPath = $outDir . '/artisan-commands.html';
        File::put($htmlPath, $html);

        // === Spinner stoppen ===============================================
        if ($spinnerPid) {
            // SIGTERM geben; Trap im Skript stellt Cursor wieder her
            shell_exec("kill -0 " . (int)$spinnerPid . " >/dev/null 2>&1 && kill " . (int)$spinnerPid);
            // Fallback: sicherstellen, dass Cursor sichtbar ist
            echo "\e[?25h\n";
        }
        // ====================================================================

        $this->info('✓ Artisan-Command-Dokumentation aktualisiert.');
        return Command::SUCCESS;
    }

    protected function collectCommands(array $categoriesFilter): array
    {
        $raw = (string)@shell_exec('php artisan list --raw 2>&1');
        $lines = preg_split('/\r\n|\r|\n/', $raw);
        $lines = array_values(array_filter(array_map('trim', $lines)));

        $byCat = [];
        foreach ($lines as $line) {
            if ($line === '' || str_starts_with($line, 'php ')) continue;

            $cmdName = trim(strtok($line, " \t"));
            if ($cmdName === '') continue;
            $desc = trim(substr($line, strlen($cmdName))) ?: '';

            $parts = explode(':', $cmdName, 2);
            $cat = count($parts) > 1 ? $parts[0] : 'misc';
            if (!empty($categoriesFilter) && !in_array($cat, $categoriesFilter)) continue;

            $help = (string)@shell_exec('php artisan help ' . escapeshellarg($cmdName) . ' 2>&1');

            if (!isset($byCat[$cat])) {
                $byCat[$cat] = [];
            }
            $byCat[$cat][] = [
                'name' => $cmdName,
                'description' => $desc,
                'help' => $help,
            ];
        }

        ksort($byCat);
        foreach ($byCat as &$arr) {
            usort($arr, fn($a, $b) => strcmp($a['name'], $b['name']));
        }
        return $byCat;
    }

    protected function renderHtml(array $byCat): string
    {
        $basePath = base_path();
        $laravelVersion = app()->version();
        $phpVersion = PHP_VERSION;

        $descFile = base_path('.logs/audits/git/audit-main-desc.txt');
        $desc = \Illuminate\Support\Facades\File::exists($descFile)
            ? \Illuminate\Support\Facades\File::get($descFile)
            : 'No description available.';
        $desc = preg_replace_callback('/<[^>]+>/', function ($m) {
            return stripos($m[0], '<span') === 0 || stripos($m[0], '</span') === 0 ? $m[0] : htmlspecialchars($m[0], ENT_QUOTES);
        }, $desc);

        $head = <<<HTML
<!DOCTYPE html>
<html lang="de">
<head>
<meta charset="UTF-8">
<title>Artisan Commands</title>
<link rel="stylesheet" href="../../audits-base.css">
<link rel="stylesheet" href="../../audits-artisan.css">
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</head>
<body>
<h1>Artisan Command Reference — tafeld</h1>
<div class="subtitle">{$desc}</div>
<div class="backlink"><a href="../../audits-main.html">Back to Audits-Main</a></div>
HTML;

        $body = '';
        foreach ($byCat as $cat => $list) {
            $body .= '<details class="category"><summary><ion-icon name="folder-outline"></ion-icon> ' .
                htmlspecialchars($cat, ENT_QUOTES) . '</summary>';
            foreach ($list as $entry) {
                $cmd = $entry['name'];
                $desc = $entry['description'] ?? '';
                $helpRaw = $entry['help'] ?? '';
                $cmdId = 'cmd-' . htmlspecialchars(str_replace(':', '-', $cmd), ENT_QUOTES);

                $helpEsc = htmlspecialchars($helpRaw, ENT_QUOTES);
                $helpEsc = preg_replace('/(^|\n)Arguments:/mi', '$1<span class="pre-arguments">Arguments:</span>', $helpEsc);
                $helpEsc = preg_replace('/(^|\n)Options:/mi', '$1<span class="pre-options">Options:</span>', $helpEsc);

                $metaRows  = '<tr><th>Command</th><td class="value-command">php artisan ' . htmlspecialchars($cmd, ENT_QUOTES) . '</td></tr>';
                $metaRows .= '<tr><th>Description</th><td class="value-desc">' . nl2br(htmlspecialchars($desc, ENT_QUOTES)) . '</td></tr>';
                $metaRows .= '<tr><th>Help</th><td class="value-help"><pre>' . $helpEsc . '</pre></td></tr>';

                $body .= '<details class="command artisan-command" id="' . $cmdId . '">'
                    . '<summary><ion-icon name="terminal-outline"></ion-icon> '
                    . '<span class="cmd-name">' . htmlspecialchars($cmd, ENT_QUOTES) . '</span>'
                    . '<span class="cmd-desc">' . htmlspecialchars($desc, ENT_QUOTES) . '</span>'
                    . '</summary>'
                    . '<table class="meta">' . $metaRows . '</table>'
                    . '</details>';
            }
            $body .= '</details>';
        }

        $body .= '<div class="backlink"><a href="../../audits-main.html">Back to Audits-Main</a></div>';

        $footer = '<footer class="doc-footer">Generated on ' . date('Y-m-d H:i:s') .
            ' | Laravel ' . $laravelVersion . ' | PHP ' . $phpVersion . '</footer>';
        return $head . $body . $footer . '</body></html>';
    }

    protected function defaultCss(): string
    {
        return <<<CSS
:root { --bg:#0f1115; --fg:#e6e6e6; --muted:#9aa; --accent:#7cc; --border:#2a2f3a; }
body { background:var(--bg); color:var(--fg); font:14px/1.5 monospace; padding:16px; }
h1 { color:var(--accent); font-size:18px; margin:0 0 12px; }

details { border:1px solid var(--border); border-radius:8px; padding:8px 12px; margin:12px 0; background:#131722; }
summary { cursor:pointer; color:var(--accent); transition:color .2s; }
summary:hover { color:#fff; text-shadow:0 0 4px #6cf; }

table { width:100%; border-collapse:collapse; margin:8px 0; }
th, td { border:1px solid var(--border); padding:6px 8px; vertical-align:top; }
th { background:#171b26; text-align:left; width:220px; color:var(--muted); }

pre { white-space:pre-wrap; background:#0f121a; padding:8px; border-radius:6px; }

.backlink { text-align:center; margin:20px 0; }
.backlink a { color:var(--accent); text-decoration:none; }
.backlink a:hover { text-decoration:underline; }

footer { margin-top:20px; text-align:center; color:var(--muted); font-size:12px; }
CSS;
    }
}
