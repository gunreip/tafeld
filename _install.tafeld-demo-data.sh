#!/usr/bin/env bash
# _install.tafeld-demo-data.v3.sh
# Robuster Seeder-Installer für tafeld (PostgreSQL)
# Standort: /home/gunreip/code/tafeld

set -euo pipefail

PROJ_ROOT="/home/gunreip/code/tafeld"
SEED_DIR="$PROJ_ROOT/database/seeders"
APP_DIR="$PROJ_ROOT/app"
EIN_FILE="$PROJ_ROOT/.einspieler.txt"
TS=$(date +"%Y%m%d-%H%M%S")

echo "[TAFELD-SEEDER-V3] Start $TS"
cd "$PROJ_ROOT"

[ -d "$SEED_DIR" ] || { echo "Fehler: Seeder-Verzeichnis fehlt."; exit 1; }

if ! php artisan --version >/dev/null 2>&1; then
  echo "Fehler: Laravel nicht initialisiert. Bitte composer install ausführen."
  exit 1
fi

mkdir -p "$PROJ_ROOT/backups"
tar czf "$PROJ_ROOT/backups/seeders.v3.$TS.tar.gz" "$SEED_DIR" 2>/dev/null || true
echo "[Backup] Gespeichert unter backups/seeders.v3.$TS.tar.gz"

create_seeder() {
  local name="$1"
  local table="$2"
  local code="$3"
  local file="$SEED_DIR/${name}.php"
  cat >"$file" <<PHP
<?php

namespace Database\\Seeders;

use Illuminate\\Database\\Seeder;
use Illuminate\\Support\\Facades\\DB;
use Illuminate\\Support\\Str;
use App\\Services\\AuditService;

class ${name} extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('${table}')) {
            echo "[WARN] Tabelle '${table}' existiert nicht, Seeder übersprungen.\n";
            return;
        }

        DB::table('${table}')->insert(${code});

        AuditService::log('seed_insert', '${table}', ['rows' => count(${code})]);
    }
}
PHP
  echo "[OK] Seeder ${name} geschrieben."
}

# Sicherstellen, dass AuditService vorhanden ist
if [ ! -f "$APP_DIR/Services/AuditService.php" ]; then
  mkdir -p "$APP_DIR/Services"
  cat >"$APP_DIR/Services/AuditService.php" <<'PHP'
<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class AuditService
{
    public static function log(string $action, string $model, array $data = []): void
    {
        $path = storage_path('logs/audit/' . date('Ymd') . '.jsonl');
        File::append($path, json_encode([
            'ts' => date('c'),
            'action' => $action,
            'model' => $model,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE) . PHP_EOL);
    }
}
PHP
  echo "[OK] AuditService neu angelegt."
fi

# Seeder-Daten
create_seeder "EmployeeSeeder" "employees" "[[
    'uuid' => Str::uuid(),
    'encrypted_data' => json_encode(['name' => 'Max Mustermann','position'=>'Leitung','department'=>'Verwaltung','entry_date'=>'2022-01-01','active'=>true]),
    'created_at' => now(),
    'updated_at' => now()
]]"

create_seeder "CustomerSeeder" "customers" "[[
    'uuid' => Str::uuid(),
    'encrypted_data' => json_encode(['name'=>'Beispielkunde GmbH','email'=>'kunde@example.com','phone'=>'+4912345678']),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "InventorySeeder" "inventory_items" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['name'=>'Laptop','sku'=>'IT-1001','quantity'=>10,'price'=>1299.00]),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "StockMovementSeeder" "stock_movements" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['inventory_item_id'=>1,'type'=>'in','quantity'=>10,'note'=>'Erstbestand']),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "CashbookSeeder" "cashbook_entries" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['entry_date'=>'2025-10-26','amount'=>1000.00,'type'=>'income','category'=>'Startkapital']),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "VehicleSeeder" "vehicles" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['identifier'=>'B-TAF 123','model'=>'Transporter','mileage'=>10000,'active'=>true]),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "WorkScheduleSeeder" "work_schedules" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['employee_id'=>1,'work_date'=>'2025-10-27','start_time'=>'08:00','end_time'=>'16:00']),
    'created_at'=>now(),'updated_at'=>now()
]]"

create_seeder "DocumentSeeder" "documents" "[[
    'uuid'=>Str::uuid(),
    'encrypted_data'=>json_encode(['title'=>'Mitarbeitervertrag','path'=>'storage/private/contracts/vertrag1.pdf','user_id'=>1]),
    'created_at'=>now(),'updated_at'=>now()
]]"

# DatabaseSeeder anpassen
DBSEED="$SEED_DIR/DatabaseSeeder.php"
if ! grep -q "EmployeeSeeder" "$DBSEED"; then
  sed -i "/run(): void {/a \        \$this->call([\n            EmployeeSeeder::class,\n            CustomerSeeder::class,\n            InventorySeeder::class,\n            StockMovementSeeder::class,\n            CashbookSeeder::class,\n            VehicleSeeder::class,\n            WorkScheduleSeeder::class,\n            DocumentSeeder::class,\n        ]);" "$DBSEED"
  echo "[DatabaseSeeder] aktualisiert."
fi

echo "$TS: _install.tafeld-demo-data.v3.sh -> Seeder ersetzt & validiert" >> "$EIN_FILE"
echo "[Fertig] Seeder-Installer v3 abgeschlossen."
echo "Weiter mit:"
echo "  php artisan migrate:fresh --seed"
