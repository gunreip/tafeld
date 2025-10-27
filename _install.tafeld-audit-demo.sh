#!/usr/bin/env bash
# _install.tafeld-audit-demo.sh
# Erstellt einfaches Audit-Logging-System mit Demo-Einträgen
# Standort: /home/gunreip/code/tafeld

set -euo pipefail

PROJ_ROOT="/home/gunreip/code/tafeld"
APP_DIR="$PROJ_ROOT/app"
LOG_DIR="$PROJ_ROOT/storage/logs/audit"
EIN_FILE="$PROJ_ROOT/.einspieler.txt"
TS=$(date +"%Y%m%d-%H%M%S")

echo "[TAFELD-AUDIT-INSTALL] Start $TS"
cd "$PROJ_ROOT"

mkdir -p "$LOG_DIR"

# --- AuditService-Klasse ---
AUDIT_FILE="$APP_DIR/Services/AuditService.php"
mkdir -p "$(dirname "$AUDIT_FILE")"

cat >"$AUDIT_FILE" <<'PHP'
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
PHP

echo "[OK] AuditService erstellt: app/Services/AuditService.php"

# --- AuditSeeder ---
SEED_DIR="$PROJ_ROOT/database/seeders"
AUDIT_SEED="$SEED_DIR/AuditSeeder.php"

cat >"$AUDIT_SEED" <<'PHP'
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Services\AuditService;
use Illuminate\Support\Facades\DB;

class AuditSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            'employees', 'customers', 'inventory_items', 'stock_movements',
            'cashbook_entries', 'vehicles', 'work_schedules', 'documents'
        ];

        foreach ($tables as $table) {
            $rows = DB::table($table)->get();
            foreach ($rows as $row) {
                AuditService::log('seed_insert', $table, ['id' => $row->id]);
            }
        }
    }
}
PHP

echo "[OK] AuditSeeder erstellt"

# --- DatabaseSeeder anpassen ---
DBSEED="$SEED_DIR/DatabaseSeeder.php"
if ! grep -q "AuditSeeder" "$DBSEED"; then
  sed -i "/run(): void {/a \        \$this->call(AuditSeeder::class);" "$DBSEED"
  echo "[DatabaseSeeder] AuditSeeder hinzugefügt."
fi

# --- Einspieler-Protokoll ---
echo "$TS: _install.tafeld-audit-demo.sh -> AuditService + AuditSeeder erstellt" >> "$EIN_FILE"

echo "[Fertig] Audit-Demo-Installer abgeschlossen."
echo "Nächste Schritte:"
echo "  1. php artisan db:seed --class=AuditSeeder"
echo "  2. tail -n 5 storage/logs/audit/$(date +%Y%m%d).jsonl"
