<?php

namespace App\Support;

class RouteAudit
{
    public static function missing($class)
    {
        $dir = base_path('.audits/routes/' . date('Y-m-d'));
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        $file = $dir . '/missing-' . date('Ymd-His') . '.json';
        $entry = [
            'timestamp' => now()->toDateTimeString(),
            'missing_class' => $class,
            'context' => 'route-registration',
        ];

        file_put_contents($file, json_encode($entry, JSON_PRETTY_PRINT));

        self::cleanup();
    }

    public static function cleanup(int $days = 30): void
    {
        $base = base_path('.audits/routes');
        if (!is_dir($base)) return;

        $threshold = now()->subDays($days);

        foreach (scandir($base) as $dir) {
            if ($dir === '.' || $dir === '..') continue;

            $path = $base . '/' . $dir;
            if (!is_dir($path)) continue;

            $dirDate = \Carbon\Carbon::parse($dir);
            if ($dirDate->lt($threshold)) {
                exec('rm -rf ' . escapeshellarg($path));
            }
        }
    }
}
