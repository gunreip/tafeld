<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectWireNavigateFixer extends Command
{
    protected $signature = 'project:wireNavigateFix {--dry-run} {--len=140}';
    protected $description = 'Automatically insert wire:navigate on internal <a> tags (structured identical to the Check command)';

    public function handle()
    {
        $projectRoot = base_path();
        $viewsRoot   = resource_path('views');
        $dryRun      = $this->option('dry-run');
        $maxLen      = (int)$this->option('len');

        $targetFile  = $projectRoot . '/.audits/laravel/vendor/livewire/wire_navigate-fix.json';

        // Basisstruktur
        $report = [
            $projectRoot => [
                'resources/views/' => []
            ]
        ];

        // Alle Blade-Dateien
        $bladeFiles = collect(File::allFiles($viewsRoot))
            ->filter(fn($f) => str_ends_with($f->getFilename(), '.blade.php'));

        foreach ($bladeFiles as $file) {

            $absolute = $file->getRealPath();
            $relative = str_replace($viewsRoot . '/', '', $absolute);
            $segments = explode('/', $relative);

            $pointer = &$report[$projectRoot]['resources/views/'];

            foreach ($segments as $i => $segment) {

                $last = ($i === count($segments) - 1);

                if ($last) {
                    // Datei verarbeiten
                    $result = $this->processFile($absolute, $dryRun, $maxLen);

                    // Datei nur aufnehmen wenn Änderungen
                    if (count($result['changes']) > 0) {
                        $pointer[$segment] = [
                            'summary' => [
                                'changed_links' => count($result['changes'])
                            ],
                            'changes' => $result['changes'],
                            '_before' => $result['before'],   // temporär
                            '_after'  => $result['after']    // temporär
                        ];
                    }
                } else {
                    // Ordner erstellen
                    $folder = $segment . '/';

                    if (!isset($pointer[$folder])) {
                        $pointer[$folder] = [];
                    }

                    $pointer = &$pointer[$folder];
                }
            }

            unset($pointer);
        }

        // Leere Ordner entfernen
        $tree = $report[$projectRoot]['resources/views/'];
        $tree = $this->removeEmptyFolders($tree);

        // before/after nur am letzten Key jedes Ordners lassen
        $tree = $this->finalizeBeforeAfter($tree);

        $report[$projectRoot]['resources/views/'] = $tree;

        File::put($targetFile, json_encode($report, JSON_PRETTY_PRINT));

        $this->info("Fix report written: {$targetFile}");
        return Command::SUCCESS;
    }


    private function processFile(string $path, bool $dryRun, int $maxLen): array
    {
        $original = File::get($path);
        $patched  = $original;
        $changes  = [];

        preg_match_all(
            '/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i',
            $original,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        foreach ($matches[0] as $match) {

            $fullTag = $match[0];

            preg_match('/href=["\']([^"\']+)["\']/i', $fullTag, $hrefMatch);
            $href = $hrefMatch[1] ?? '';

            // externe Links ignorieren
            if (preg_match('/^https?:\/\//i', $href)) {
                continue;
            }

            // schon wire:navigate?
            if (preg_match('/wire:navigate/i', $fullTag)) {
                continue;
            }

            // neuen Tag erzeugen
            $newTag = preg_replace('/<a\s+/i', '<a wire:navigate ', $fullTag, 1);

            $changes[] = [
                'href'   => $href,
                'before' => $fullTag,
                'after'  => $newTag,
            ];

            // ersten Fund ersetzen
            $patched = $this->strReplaceFirst($fullTag, $newTag, $patched);
        }

        if (!$dryRun && count($changes) > 0) {
            File::put($path, $patched);
        }

        return [
            'changes' => $changes,
            'before'  => $this->shorten($original, $maxLen),
            'after'   => $this->shorten($patched, $maxLen)
        ];
    }


    private function finalizeBeforeAfter(array $tree): array
    {
        // 1. Ordner und Dateien trennen
        $folderKeys = [];
        $fileKeys   = [];

        foreach ($tree as $key => $value) {
            if (is_array($value) && $this->isFileEntry($value)) {
                $fileKeys[] = $key;
            } elseif (is_array($value)) {
                $folderKeys[] = $key;
            }
        }

        // 2. Letzte Datei bestimmen (falls vorhanden)
        $lastFileKey = count($fileKeys) ? end($fileKeys) : null;

        // 3. Datei-Einträge bereinigen (alle außer letzter → remove before/after)
        foreach ($fileKeys as $key) {

            // Nur Datei-Eintrag?
            if (!$this->isFileEntry($tree[$key])) {
                continue;
            }

            // Wenn _before/_after existieren → ggf. löschen oder übernehmen
            if (array_key_exists('_before', $tree[$key]) || array_key_exists('_after', $tree[$key])) {

                if ($key === $lastFileKey) {
                    // letzter Datei-Eintrag → übernehmen
                    $tree[$key]['before'] = $tree[$key]['_before'] ?? '';
                    $tree[$key]['after']  = $tree[$key]['_after']  ?? '';
                }

                // immer löschen
                unset($tree[$key]['_before'], $tree[$key]['_after']);
            }
        }

        // 4. Ordner rekursiv säubern
        foreach ($folderKeys as $key) {
            $tree[$key] = $this->finalizeBeforeAfter($tree[$key]);
        }

        return $tree;
    }


    private function isFileEntry(array $value): bool
    {
        return isset($value['summary']) && isset($value['changes']);
    }


    private function shorten(string $text, int $len): string
    {
        return strlen($text) > $len
            ? substr($text, 0, $len) . " …"
            : $text;
    }


    private function strReplaceFirst(string $search, string $replace, string $subject): string
    {
        $pos = strpos($subject, $search);
        return $pos === false
            ? $subject
            : substr($subject, 0, $pos) . $replace . substr($subject, $pos + strlen($search));
    }


    private function removeEmptyFolders(array $tree): array
    {
        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                $tree[$key] = $this->removeEmptyFolders($value);

                if (empty($tree[$key])) {
                    unset($tree[$key]);
                }
            }
        }
        return $tree;
    }
}
