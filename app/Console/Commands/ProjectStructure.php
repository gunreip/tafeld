<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class ProjectStructure extends Command
{
    protected $signature = 'project:structure {--gitignore-strict} {--dirsfirst=yes}';
    protected $description = 'Generiert eine verschachtelte, sortierte Projektstruktur als HTML und JSON Audit.';

    private int $dirCount = 0;
    private int $fileCount = 0;
    private float $totalSize = 0.0;

    public function handle(): int
    {
        $basePath = base_path();
        $envFile = $basePath . '/.env';
        $projectName = null;

        if (File::exists($envFile)) {
            foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
                if (str_starts_with($line, 'PROJ_NAME=')) {
                    $projectName = trim(substr($line, 10));
                    break;
                }
            }
            if (!$projectName) $this->warn('⚠ .env enthält kein PROJ_NAME, benutze aktuellen Ordnernamen.');
        } else {
            $this->warn('⚠ Keine .env gefunden, benutze aktuellen Ordnernamen.');
        }

        $projectName = $projectName ?: basename($basePath);
        $logDir = $basePath . '/.logs/structure';
        File::ensureDirectoryExists($logDir);

        $htmlFile = "$logDir/{$projectName}-struct.html";
        $jsonFile = "$logDir/{$projectName}-struct.json";

        $strict = $this->option('gitignore-strict');
        $dirsFirst = $this->option('dirsfirst') !== 'no';

        $gitignore = [];
        if (File::exists($basePath . '/.gitignore')) {
            $gitignore = array_filter(array_map('trim', file($basePath . '/.gitignore')), fn($l) => $l && !str_starts_with($l, '#'));
        }

        $defaultExcludes = ['.idea', '.vscode', '.DS_Store', '*.log', '*.bak', '*.tmp', '*.swp'];
        $excludes = $strict ? $gitignore : array_merge($gitignore, $defaultExcludes);

        $flat = $this->scan($basePath, $excludes);
        $tree = $this->buildTree($flat);
        $tree = $this->sortTree($tree, $dirsFirst);

        $this->saveJson($jsonFile, $tree);
        $this->saveHtml($htmlFile, $tree, $projectName, $basePath);

        $this->info("✓ Struktur erstellt: $htmlFile");
        return Command::SUCCESS;
    }

    private function scan(string $path, array $excludes): array
    {
        $result = [];
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            /** @var SplFileInfo $item */
            $relPath = str_replace($path . '/', '', $item->getPathname());

            if ($this->isExcluded($relPath, $excludes)) continue;
            if (str_starts_with($relPath, 'node_modules/') && substr_count($relPath, '/') > 1) continue;
            if (str_starts_with($relPath, 'vendor/') && substr_count($relPath, '/') > 1) continue;
            if (str_starts_with($relPath, '.git/') && substr_count($relPath, '/') > 1) continue;

            $result[] = [
                'path' => $relPath,
                'type' => $item->isDir() ? 'dir' : 'file',
                'size' => $item->isDir() ? 0 : $item->getSize()
            ];
        }
        return $result;
    }

    private function isExcluded(string $path, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            $pattern = str_replace(['*', '/'], ['.*', '\/'], $pattern);
            if (@preg_match("/^$pattern$/i", $path)) return true;
        }
        return false;
    }

    private function buildTree(array $flat): array
    {
        $tree = [];
        foreach ($flat as $item) {
            $parts = explode('/', $item['path']);
            $ref = &$tree;
            foreach ($parts as $i => $part) {
                if (!isset($ref[$part])) $ref[$part] = [];
                if ($i === count($parts) - 1) {
                    if ($item['type'] === 'file') {
                        $ref[$part] = $item['size'];
                        $this->fileCount++;
                        $this->totalSize += $item['size'];
                    } else {
                        $this->dirCount++;
                    }
                } else {
                    $ref = &$ref[$part];
                }
            }
        }
        return $tree;
    }

    private function sortTree(array $tree, bool $dirsFirst): array
    {
        uksort($tree, 'strnatcasecmp');

        foreach ($tree as $key => &$value) {
            if (is_array($value)) {
                $value = $this->sortTree($value, $dirsFirst);
            }
        }

        if ($dirsFirst) {
            $dirs = array_filter($tree, 'is_array');
            $files = array_filter($tree, fn($v) => !is_array($v));
            return array_merge($dirs, $files);
        }

        return $tree;
    }

    private function formatSize(float $bytes): string
    {
        if ($bytes <= 0) return '0,0 B';
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $exp = floor(log($bytes, 1024));
        $size = $bytes / pow(1024, $exp);
        return number_format($size, 1, ',', ' ') . ' ' . $units[$exp];
    }

    private function directorySize(array $tree): float
    {
        $size = 0.0;
        foreach ($tree as $value) {
            if (is_array($value)) {
                $size += $this->directorySize($value);
            } else {
                $size += (float)$value;
            }
        }
        return $size;
    }

    private function saveJson(string $target, array $data): void
    {
        file_put_contents($target, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    private function saveHtml(string $target, array $tree, string $projectName, string $basePath): void
    {
        $laravelVersion = app()->version();
        $phpVersion = PHP_VERSION;

        // Untertitel aus audit-main-desc.txt (wenn vorhanden) roh einbinden
        $subtitlePath = $basePath . '/.logs/structure/audit-main-desc.txt';
        $subtitleHtml = '';
        if (File::exists($subtitlePath)) {
            $subtitleHtml = trim(File::get($subtitlePath)); // NICHT escapen, darf HTML enthalten
        }

        $html = [];
        $html[] = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8">';
        $html[] = "<title>Projektstruktur – $projectName</title>";
        $html[] = '<link rel="stylesheet" href="../audits-base.css">';
        $html[] = '<link rel="stylesheet" href="../project-structure.css">';
        $html[] = '<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>';
        $html[] = '<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>';
        $html[] = '</head><body>';
        $html[] = "<h1>Projektstruktur – $projectName</h1>";
        if ($subtitleHtml !== '') {
            $html[] = '<p class="subtitle">' . $subtitleHtml . '</p>'; // bewusst unescaped
        }
        $html[] = '<a href="../audits-main.html" class="backlink">← zurück zur Audit-Übersicht</a>';
        $html[] = '<section class="project-structure">';
        $html[] = '<p class="project-path">' . htmlspecialchars($basePath) . '</p>';
        $html[] = $this->renderTree($tree, true);
        $html[] = '</section>';
        $html[] = '<a href="../audits-main.html" class="backlink">← zurück zur Audit-Übersicht</a>';
        $html[] = $this->renderSummary();
        $html[] = '<footer class="doc-footer">Generated on ' . date('Y-m-d H:i:s') .
            ' | Laravel ' . $laravelVersion . ' | PHP ' . $phpVersion . '</footer>';
        $html[] = '</body></html>';
        file_put_contents($target, implode('', $html));
    }

    private function renderTree(array $tree, bool $isRoot = false): string
    {
        $html = $isRoot ? '<ul class="tree">' : '<ul>';
        foreach ($tree as $name => $children) {
            $isDir = is_array($children);
            $icon = $isDir ? 'folder-outline' : 'document-text-outline';
            $size = $isDir ? $this->directorySize($children) : (float)$children;
            $html .= '<li class="' . ($isDir ? 'dir' : 'file') . '"><ion-icon name="' . $icon . '"></ion-icon>' . $name;
            $html .= ' <span class="size">[' . $this->formatSize($size) . ']</span>';
            if ($isDir && !empty($children)) {
                $html .= $this->renderTree($children);
            }
            $html .= '</li>';
        }
        $html .= '</ul>';
        return $html;
    }

    private function renderSummary(): string
    {
        $html = '<section id="summary">';
        $html .= '<h2>Zusammenfassung</h2>';
        $html .= '<ul>';
        $html .= '<li>Verzeichnisse: ' . number_format($this->dirCount, 0, ',', '.') . '</li>';
        $html .= '<li>Dateien: ' . number_format($this->fileCount, 0, ',', '.') . '</li>';
        $html .= '<li>Gesamtgröße: ' . $this->formatSize($this->totalSize) . '</li>';
        $html .= '</ul></section>';
        return $html;
    }
}
