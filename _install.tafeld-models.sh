#!/usr/bin/env bash
# _install.tafeld-models.sh
# Erstellt Models + Migrations für tafeld-Projekt
# Standort: /home/gunreip/code/tafeld

set -euo pipefail

PROJ_ROOT="/home/gunreip/code/tafeld"
MODEL_DIR="$PROJ_ROOT/app/Models"
MIG_DIR="$PROJ_ROOT/database/migrations"
EIN_FILE="$PROJ_ROOT/.einspieler.txt"
TS=$(date +"%Y%m%d-%H%M%S")

echo "[TAFELD-INSTALL] Start $TS"
cd "$PROJ_ROOT"

# --- Verzeichnisprüfung ---
for d in "$MODEL_DIR" "$MIG_DIR"; do
  [ -d "$d" ] || { echo "Fehler: Verzeichnis fehlt: $d"; exit 1; }
done

# --- Composer Check ---
if ! command -v composer >/dev/null; then
  echo "Fehler: composer fehlt im PATH."
  exit 1
fi
if ! php artisan --version >/dev/null 2>&1; then
  echo "Fehler: Laravel nicht initialisiert oder vendor fehlt."
  echo "→ Führe 'composer install' im Projekt aus und starte erneut."
  exit 1
fi

# --- Backup vorhandener Dateien ---
mkdir -p "$PROJ_ROOT/backups"
tar czf "$PROJ_ROOT/backups/models-migrations.$TS.tar.gz" \
  "$MODEL_DIR" "$MIG_DIR" 2>/dev/null || true
echo "[Backup] Gespeichert unter backups/models-migrations.$TS.tar.gz"

# --- Artisan-Wrapper mit sichtbarem Output ---
run_artisan() {
  echo "[Artisan] php artisan $*"
  if ! php artisan "$@"; then
    echo "FEHLER: artisan $* schlug fehl."
    echo "→ Abbruch. Bitte obenstehende Ausgabe prüfen."
    exit 1
  fi
}

# --- Models und Migrations erzeugen ---
MODELS=(Employee Customer InventoryItem StockMovement CashbookEntry Vehicle WorkSchedule Document)

for M in "${MODELS[@]}"; do
  run_artisan make:model "$M" -m
done

# --- Migrationstemplates vereinheitlichen ---
echo "[Patch] Migrationen mit Standardstruktur versehen"

generate_migration() {
  local name="$1"
  local file
  file=$(ls -1 "$MIG_DIR"/*create_${name}_table.php 2>/dev/null | head -n1)
  [ -f "$file" ] || return

  cat >"$file" <<'PHP'
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('__TABLE__', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->jsonb('encrypted_data')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void {
        Schema::dropIfExists('__TABLE__');
    }
};
PHP
  sed -i "s/__TABLE__/${name}/" "$file"
  echo "[OK] Vorlage für ${name} gesetzt."
}

generate_migration "employees"
generate_migration "customers"
generate_migration "inventory_items"
generate_migration "stock_movements"
generate_migration "cashbook_entries"
generate_migration "vehicles"
generate_migration "work_schedules"
generate_migration "documents"

# --- Audit-Eintrag ---
echo "[Einspieler] Eintrag hinzufügen"
echo "$TS: _install.tafeld-models.sh -> Models & Migrations erstellt" >> "$EIN_FILE"

echo "[Fertig] Alle Models und Migrations wurden erstellt."
echo "Weiter mit:"
echo "  1. php artisan migrate:fresh"
echo "  2. php artisan db:seed (optional)"
echo "  3. git add . && git commit -m 'Init tafeld models + migrations'"
