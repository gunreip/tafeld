<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectTreeCommand extends Command
{
    protected $signature = 'project:tree {dir}';
    protected $description = 'Write project directory tree to .audits/tree/*.json';

    public function handle()
    {
        $root = base_path();
        $target = $this->argument('dir');

        $dirMap = [
            'tafeld'       => [$root, 0],
            'vendor'       => [$root . '/vendor', 0],
            'node_modules' => [$root . '/node_modules', 0],
        ];

        if (isset($dirMap[$target])) {
            [$scanPath, $depth] = $dirMap[$target];
        } else {
            $scanPath = $root . '/' . ltrim($target, '/');
            $depth = PHP_INT_MAX;
        }

        if (! File::exists($scanPath)) {
            $this->error('Directory not found.');
            return Command::FAILURE;
        }

        $outputBase = $root . '/.audits/tree';
        File::ensureDirectoryExists($outputBase);

        $tree = [
            'root'  => $target,
            'path'  => $scanPath,
            'items' => $this->scan($scanPath, $depth, $target),
        ];

        $outFile = $outputBase . '/tree-' . $target . '.json';
        File::put($outFile, json_encode($tree, JSON_PRETTY_PRINT));

        $this->info("Written: $outFile");
        return Command::SUCCESS;
    }

    protected function scan(string $path, int $maxDepth, string $rootKey, int $level = 0)
    {
        if ($level > $maxDepth) return [];

        $nodes = [];

        // vendor Versionsnummern
        $vendorVersions = [];
        if ($rootKey === 'vendor') {
            $lock = json_decode(File::get(base_path('composer.lock')), true);
            foreach ($lock['packages'] as $pkg) {
                $vendorVersions[$pkg['name']] = $pkg['version'];
            }
        }

        // node_modules Versionsnummern
        $nodeVersions = [];
        if ($rootKey === 'node_modules' && File::exists(base_path('package-lock.json'))) {
            $lock = json_decode(File::get(base_path('package-lock.json')), true);
            foreach ($lock['packages'] as $pkg => $meta) {
                if (str_starts_with($pkg, 'node_modules/')) {
                    $name = basename($pkg);
                    $nodeVersions[$name] = $meta['version'] ?? null;
                }
            }
        }

        foreach (File::directories($path) as $dir) {
            $name = basename($dir);

            $entry = [
                'type'  => 'dir',
                'name'  => $name,
            ];

            if ($rootKey === 'vendor') {
                $entry['version'] = $vendorVersions["{$name}/{$name}"] ?? null;
            }

            if ($rootKey === 'node_modules') {
                $entry['version'] = $nodeVersions[$name] ?? null;
            }

            $entry['items'] = $this->scan($dir, $maxDepth, $rootKey, $level + 1);
            $nodes[] = $entry;
        }

        foreach (File::files($path) as $file) {
            $info = $file->getFileInfo();

            $modified = $info->getMTime();
            $created  = $info->getCTime();

            $nodes[] = [
                'type'          => 'file',
                'name'          => $info->getFilename(),
                'extension'     => $info->getExtension(),
                'size'          => $info->getSize(),

                'modified_at'      => $modified,
                'modified_at_iso'  => date('c', $modified),

                'created_at'       => $created,
                'created_at_iso'   => date('c', $created),
            ];
        }

        return $nodes;
    }
}
