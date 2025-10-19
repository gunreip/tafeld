<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectStructure extends Command
{
    protected $signature = 'project:structure {--dir=} {--format=txt}';
    protected $description = 'Erstellt eine Verzeichnisstruktur (.txt, .html, .json) unter .logs/structure/<project>-struct[-<dir>].<ext>';

    public function handle(): int
    {
        $project = env('PROJ_NAME', basename(base_path()));
        $targetDir = base_path('.logs/structure');
        File::ensureDirectoryExists($targetDir);

        $dirOpt = $this->option('dir');
        $scanDir = $dirOpt ? base_path($dirOpt) : base_path();
        if (!File::exists($scanDir)) {
            $this->error("Verzeichnis nicht gefunden: {$scanDir}");
            return Command::FAILURE;
        }

        $format = strtolower($this->option('format'));
        if (!in_array($format, ['txt', 'html', 'json'])) {
            $this->error("Ungültiges Format: {$format}");
            return Command::FAILURE;
        }

        $suffix = $dirOpt ? '-' . str_replace('/', '_', $dirOpt) : '';
        $outputFile = "{$targetDir}/{$project}-struct{$suffix}.{$format}";

        $treeArray = $this->scanDirectory($scanDir);
        $output = $this->render($treeArray, $format, $project, $dirOpt);

        File::put($outputFile, $output);
        $this->info("Struktur gespeichert: {$outputFile}");
        return Command::SUCCESS;
    }

    /** Rekursive Directory-Erfassung als Array */
    protected function scanDirectory(string $path): array
    {
        $basename = basename($path);
        $result = [
            'name' => $basename,
            'type' => 'dir',
            'children' => [],
        ];

        // vendor/node_modules nur 1 Ebene tief
        if (in_array($basename, ['vendor', 'node_modules'])) {
            foreach (File::directories($path) as $sub) {
                $result['children'][] = ['name' => basename($sub), 'type' => 'dir'];
            }
            return $result;
        }

        foreach (File::directories($path) as $dir) {
            $result['children'][] = $this->scanDirectory($dir);
        }

        foreach (File::files($path) as $file) {
            $result['children'][] = [
                'name' => $file->getFilename(),
                'type' => 'file',
            ];
        }

        return $result;
    }

    /** Ausgabe je nach Format */
    protected function render(array $tree, string $format, string $project, ?string $dirOpt): string
    {
        switch ($format) {
            case 'json':
                return json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            case 'html':
                return $this->renderHtml($tree, $project, $dirOpt);
            default:
                return $this->renderText($tree);
        }
    }

    /** Textdarstellung ähnlich `tree` */
    protected function renderText(array $tree, int $depth = 0): string
    {
        $prefix = str_repeat('│  ', $depth);
        $output = $prefix . '├─ ' . $tree['name'] . "\n";

        if (!empty($tree['children'])) {
            foreach ($tree['children'] as $child) {
                $output .= $this->renderText($child, $depth + 1);
            }
        }
        return $output;
    }

    /** HTML-Ausgabe mit einfacher Baumstruktur */
    protected function renderHtml(array $tree, string $project, ?string $dirOpt): string
    {
        $title = htmlspecialchars($project . ($dirOpt ? " – {$dirOpt}" : ''), ENT_QUOTES);
        $list = $this->htmlList($tree);
        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{$title} Struktur</title>
<style>
body { font-family: monospace; background:#111; color:#ddd; padding:1rem; }
ul { list-style-type:none; margin-left:1rem; }
li::before { content:"├─ "; color:#888; }
.dir { color:#9cf; }
.file { color:#ccc; }
</style>
</head>
<body>
<h2>{$title}</h2>
{$list}
</body>
</html>
HTML;
    }

    /** Hilfsfunktion für HTML-Baum */
    protected function htmlList(array $node): string
    {
        $cls = $node['type'] === 'dir' ? 'dir' : 'file';
        $html = "<li class=\"{$cls}\">" . htmlspecialchars($node['name'], ENT_QUOTES) . "</li>";

        if (!empty($node['children'])) {
            $html .= "<ul>";
            foreach ($node['children'] as $child) {
                $html .= $this->htmlList($child);
            }
            $html .= "</ul>";
        }
        return $html;
    }
}
