<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectAuditMain extends Command
{
    protected $signature = 'project:audit-main';
    protected $description = 'Erzeugt eine zentrale HTML-Übersicht aller Audit-HTMLs unter .logs/* (neuschreiben bei jedem Lauf).';

    public function handle(): int
    {
        $root = base_path('.logs');
        File::ensureDirectoryExists($root);

        $cssPath = base_path('.logs/audits-main.css');
        if (!File::exists($cssPath)) {
            File::put($cssPath, $this->defaultCss());
        }

        $files = $this->collectHtmlFiles($root);
        $byDir = [];
        foreach ($files as $absPath) {
            $rel = ltrim(str_replace($root, '', $absPath), DIRECTORY_SEPARATOR);
            $dir = trim(dirname($rel), '/\\');
            if ($dir === '.') {
                $dir = '';
            }
            $byDir[$dir] ??= [];
            $byDir[$dir][] = [
                'name' => basename($rel),
                'path' => $absPath,
            ];
        }

        ksort($byDir);
        foreach ($byDir as &$list) {
            usort($list, fn($a, $b) => strcmp($a['name'], $b['name']));
        }

        $html = $this->renderHtml($byDir);
        $out = $root . '/audits-main.html';
        File::put($out, $html);

        $this->info('✓ audits-main.html aktualisiert');
        $this->line('Pfad: ' . $out);
        return Command::SUCCESS;
    }

    protected function collectHtmlFiles(string $root): array
    {
        $result = [];
        $rii = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($rii as $file) {
            if ($file->isFile() && strtolower($file->getExtension()) === 'html') {
                if (basename($file->getPathname()) === 'audits-main.html') {
                    continue;
                }
                $result[] = $file->getPathname();
            }
        }
        return $result;
    }

    protected function renderHtml(array $byDir): string
    {
        $head = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8">'
            . '<title>Tafeld Audit Übersicht</title>'
            . '<link rel="stylesheet" href="./audits-main.css">'
            . '<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>'
            . '<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>'
            . '</head><body><h1>Tafeld Audit Übersicht</h1>';

        $root = base_path('.logs');
        $body = '';

        foreach ($byDir as $dir => $files) {
            $dirDisp = $dir === '' ? '.' : $dir;
            $descFile = rtrim($root . DIRECTORY_SEPARATOR . $dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'audit-main-desc.txt';
            File::ensureDirectoryExists(dirname($descFile));
            if (!File::exists($descFile)) {
                File::put($descFile, '<span class="no-comment-yet">Noch kein Kommentar!!!</span>');
            }
            $commentRaw = File::get($descFile);

            $summary = '<summary><ion-icon name="folder-outline"></ion-icon> ' . htmlspecialchars($dirDisp, ENT_QUOTES) . '</summary>';
            $body .= '<details class="audit-folder">' . $summary . '<ul>';

            foreach ($files as $entry) {
                $file = $entry['name'];
                $absPath = $entry['path'];
                $href = ($dir === '' ? './' : './' . str_replace('\\', '/', $dir) . '/') . $file;
                $mtime = @filemtime($absPath);
                $stamp = $mtime ? date('Y-m-d H:i:s', $mtime) : 'n/a';
                $link = '<span class="project-audit-main-link"><a href="' . htmlspecialchars($href, ENT_QUOTES) . '">' . htmlspecialchars($file, ENT_QUOTES) . '</a></span>';
                $desc = '<span class="audit-desc">' . $commentRaw . '</span>';
                $ts = '<span class="audit-timestamp">' . htmlspecialchars($stamp, ENT_QUOTES) . '</span>';
                $body .= '<li>' . $link . ' ' . $desc . ' ' . $ts . '</li>';
            }
            $body .= '</ul></details>';
        }

        $footer = '<footer class="doc-footer">Generated on ' . date('Y-m-d H:i:s') . '</footer>';
        return $head . $body . $footer . '</body></html>';
    }

    protected function defaultCss(): string
    {
        return <<<CSS
:root {
    --bg: #0f1115;
    --fg: #e6e6e6;
    --muted: #9aa;
    --accent: #7cc;
    --border: #2a2f3a;
}

body {
    background: var(--bg);
    color: var(--fg);
    font: 14px/1.5 monospace;
    padding: 16px;
}

h1 {
    color: var(--accent);
    font-size: 18px;
    margin: 0 0 12px;
}

details {
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 8px 12px;
    margin: 12px 0;
    background: #131722;
}

summary {
    cursor: pointer;
    color: var(--accent);
    transition: color .2s;
}

summary:hover {
    color: #fff;
    text-shadow: 0 0 4px #6cf;
}

ul {
    list-style: none;
    padding-left: 0;
    margin: 8px 0 0 0;
}

li {
    padding: 4px 0;
    border-bottom: 1px dashed var(--border);
    display: grid;
    grid-template-columns: max-content 1fr auto;
    column-gap: 8px;
    align-items: baseline;
}

li:last-child {
    border-bottom: none;
}

span a {
    width: 30rem;
    display: inline-block;
}

span.code-commands,
span.project-placeholder {
    color: #29B6F6;
}

span.audit-main-desc,
span.class-git {
    color: #FFEE58;
}

span.file-format {
    color: #AB47BC;
}

.audit-folder>summary ion-icon {
    position: relative;
    top: -2px;
    margin-right: 6px;
    font-size: 16px;
    color: #7cc;
    vertical-align: middle;
}

.project-audit-main-link a {
    color: #7cc;
    text-decoration: none;
}

.project-audit-main-link a:hover {
    color: #fff;
    text-shadow: 0 0 4px #6cf;
}

.no-comment-yet {
    color: #999;
    font-style: italic;
}

.audit-desc {
    margin-left: 8px;
    color: #9aa;
}

.audit-timestamp {
    color: #777;
    font-size: 0.9em;
    font-style: italic;
    white-space: nowrap;
    margin-left: 3rem;
}

.doc-footer {
    margin-top: 16px;
    color: #9aa;
    text-align: right;
}
CSS;
    }
}
