<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectWireNavigateCheck extends Command
{
    protected $signature = 'project:wireNavigateCheck {--len=140}';
    protected $description = 'Scan Blade files for <a> links with/without wire:navigate (clean structured audit)';

    public function handle()
    {
        $projectRoot = base_path();
        $auditDir    = $projectRoot . '/.audits/laravel/vendor/livewire';
        File::ensureDirectoryExists($auditDir);

        $targetFile  = $auditDir . '/wire_navigate-check.json';
        $viewsRoot   = resource_path('views');
        $maxLen      = (int) $this->option('len');

        $report = [
            $projectRoot => [
                'resources/views/' => []
            ]
        ];

        $bladeFiles = collect(File::allFiles($viewsRoot))
            ->filter(fn($file) => str_ends_with($file->getFilename(), '.blade.php'));

        foreach ($bladeFiles as $file) {

            $absolute = $file->getRealPath();
            $relative = str_replace($viewsRoot . '/', '', $absolute);
            $segments = explode('/', $relative);

            $pointer = &$report[$projectRoot]['resources/views/'];

            foreach ($segments as $i => $segment) {
                $last = ($i === count($segments) - 1);

                if ($last) {
                    // Datei-Scan
                    $fileReport = $this->scanFile($absolute, $maxLen);

                    // Datei nur einfügen, wenn echte Fundstellen existieren
                    if (
                        $fileReport['summary']['internal_with'] > 0 ||
                        $fileReport['summary']['internal_without'] > 0 ||
                        $fileReport['summary']['external'] > 0
                    ) {

                        $pointer[$segment] = $fileReport;
                    }
                } else {
                    // Ordner
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
        $report[$projectRoot]['resources/views/'] =
            $this->removeEmptyFolders($report[$projectRoot]['resources/views/']);

        File::put($targetFile, json_encode($report, JSON_PRETTY_PRINT));

        $this->info("Report written: {$targetFile}");
        return Command::SUCCESS;
    }

    private function scanFile(string $path, int $len): array
    {
        $content = File::get($path);
        $lines   = explode("\n", $content);

        preg_match_all(
            '/<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i',
            $content,
            $matches,
            PREG_OFFSET_CAPTURE
        );

        $summary = [
            'internal_with'    => 0,
            'internal_without' => 0,
            'external'         => 0,
        ];

        $fundstellen = [];

        foreach ($matches[0] as $match) {

            $fullTag = $match[0];
            $offset  = $match[1];

            preg_match('/href=["\']([^"\']+)["\']/i', $fullTag, $hrefMatch);
            $href = $hrefMatch[1] ?? '';

            $external = preg_match('/^https?:\/\//i', $href) === 1;
            $withWire = preg_match('/wire:navigate/i', $fullTag) === 1;

            $lineNum = substr_count(substr($content, 0, $offset), "\n") + 1;

            if ($external) {
                $summary['external']++;
            } else {
                if ($withWire) {
                    $summary['internal_with']++;
                } else {
                    $summary['internal_without']++;
                }
            }

            $line = $lines[$lineNum - 1] ?? '';

            $fundstellen[] = [
                'line'    => $lineNum,
                'href'    => $href,
                'wire'    => $withWire,
                'snippet' => $this->shortSnippet($line, $len),
            ];
        }

        return [
            'summary'     => $summary,
            'fundstellen' => $fundstellen,
        ];
    }

    private function shortSnippet(string $line, int $len): string
    {
        return strlen($line) > $len
            ? substr($line, 0, $len) . ' …'
            : $line;
    }

    private function removeEmptyFolders(array $tree): array
    {
        foreach ($tree as $key => $value) {
            if (is_array($value)) {
                $tree[$key] = $this->removeEmptyFolders($value);

                if (empty($tree[$key])) {
                    unset($tree[$key]); // Ordner löschen
                }
            }
        }

        return $tree;
    }
}
