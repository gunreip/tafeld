<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class AuditService
{
    public static function log(string $action, string $model, array $data = []): void
    {
        $date = date('Ymd');
        $path = storage_path("logs/audit/{$date}.jsonl");
        $entry = [
            'timestamp' => date('c'),
            'action' => $action,
            'model' => $model,
            'data' => $data,
        ];
        File::append($path, json_encode($entry, JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
