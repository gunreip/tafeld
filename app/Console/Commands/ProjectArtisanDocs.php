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
        $head = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8">'
              . '<title>Laravel Artisan Command Reference</title>'
              . '<link rel="stylesheet" href="../../audits-artisan.css">'
              . '<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>'
              . '<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>'
              . '</head><body><h1>Laravel Artisan Command Reference</h1>';

        $body = '';

        foreach ($byCat as $cat => $list) {
            $catId = 'cat-' . htmlspecialchars($cat, ENT_QUOTES);
            $body .= '<details class="category artisan-category" id="'.$catId.'">'
                   . '<summary><ion-icon name="folder-outline"></ion-icon> '.htmlspecialchars($cat, ENT_QUOTES).'</summary>';

            foreach ($list as $entry) {
                $cmd = $entry['name'];
                $desc = $entry['description'] ?? '';
                $helpRaw = $entry['help'] ?? '';
                $cmdId = 'cmd-' . htmlspecialchars(str_replace(':', '-', $cmd), ENT_QUOTES);

                $helpEsc = htmlspecialchars($helpRaw, ENT_QUOTES);
                $helpEsc = preg_replace('/(^|\n)Arguments:/mi', '$1<span class="pre-arguments">Arguments:</span>', $helpEsc);
                $helpEsc = preg_replace('/(^|\n)Options:/mi', '$1<span class="pre-options">Options:</span>', $helpEsc);
                $helpEsc = preg_replace('/(^|\n)Usage:/mi', '$1<span class="pre-usage">Usage:</span>', $helpEsc);
                $helpEsc = preg_replace('/(^|\n)Description:/mi', '$1<span class="pre-description">Description:</span>', $helpEsc);

                $metaRows  = '<tr><th>Command</th><td class="value-command">php artisan '.htmlspecialchars($cmd, ENT_QUOTES).'</td></tr>';
                $metaRows .= '<tr><th>Description</th><td class="value-desc">'.nl2br(htmlspecialchars($desc, ENT_QUOTES)).'</td></tr>';
                $metaRows .= '<tr><th>Help</th><td class="value-help"><pre>'.$helpEsc.'</pre></td></tr>';

                $body .= '<details class="command artisan-command" id="'.$cmdId.'">'
                       . '<summary><ion-icon name="terminal-outline"></ion-icon> '
                       . '<span class="cmd-name">'.htmlspecialchars($cmd, ENT_QUOTES).'</span>'
                       . '<span class="cmd-desc">'.htmlspecialchars($desc, ENT_QUOTES).'</span>'
                       . '</summary>'
                       . '<table class="meta">'.$metaRows.'</table>'
                       . '</details>';
            }
            $body .= '</details>';
        }

        $footer = '<footer class="doc-footer">Generated on '.date('Y-m-d H:i:s').'</footer>';
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

.artisan-category > summary ion-icon,
.artisan-command > summary ion-icon {
    color:#7cc; font-size:16px; margin-right:6px; position:relative; top:-2px; vertical-align:middle;
}

.cmd-name { width:30rem; font-weight:600; letter-spacing:0.5px; color:#fff; display:inline-block; }
.cmd-desc { color:#aaa; font-size:.9em; margin-left:8px; opacity:.8; }

.value-command { color:#fff; }
.value-desc { color:#ddd; }

.value-help pre {
    color:#aaa; white-space:pre-wrap; background:#0c0f16;
    padding:8px; border-radius:6px; border:1px solid var(--border);
}

.pre-arguments { color:#7cf; font-weight:600; }
.pre-options { color:#fc6; font-weight:600; }
.pre-description { color:#9cf; font-weight:600; }
.pre-usage { color:#9f7; font-weight:600; }

.doc-footer { margin-top:16px; color:#9aa; text-align:right; }
CSS;
    }
}
