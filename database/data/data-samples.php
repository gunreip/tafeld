<?php
// Erstellt verschlüsselte Testdaten in allen Tabellen
// Pfad: database/data/data-samples.php
// Ausführung: include_once('database/data/data-samples.php');                // Testdaten neu anlegen

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\{
    Employee,
    Customer,
    InventoryItem,
    StockMovement,
    CashbookEntry,
    Vehicle,
    WorkSchedule,
    Document
};

date_default_timezone_set('Europe/Berlin');

$basePath = database_path('data');
File::ensureDirectoryExists($basePath);

echo "TAFELD TESTMODUS – bestehende Daten werden überschrieben.\n";

// ---------------------------
// 1. Tabellen leeren
// ---------------------------
DB::statement('SET session_replication_role = replica;');
$tables = [
    'employees',
    'customers',
    'inventory_items',
    'stock_movements',
    'cashbook_entries',
    'vehicles',
    'work_schedules',
    'documents',
];
foreach ($tables as $table) {
    DB::table($table)->truncate();
}
DB::statement('SET session_replication_role = DEFAULT;');

// ---------------------------
// 2. Beispiel-/Testdatensätze
// ---------------------------

// Employees
Employee::create(['encrypted_data' => ['name' => 'Max Mustermann', 'role' => 'Leitung', 'department' => 'Verwaltung']]);
Employee::create(['encrypted_data' => ['name' => 'Erika Beispiel', 'role' => 'Sachbearbeitung', 'department' => 'Finanzen']]);
Employee::create(['encrypted_data' => ['name' => 'Jonas Meyer', 'role' => 'Technik', 'department' => 'Produktion']]);

// Customers
Customer::create(['encrypted_data' => ['name' => 'Beispielkunde GmbH', 'email' => 'info@beispielkunde.de', 'phone' => '+49 123 456789']]);
Customer::create(['encrypted_data' => ['name' => 'Demo AG', 'email' => 'kontakt@demo-ag.de', 'phone' => '+49 987 654321']]);

// Inventory items
InventoryItem::create(['encrypted_data' => ['name' => 'Notebook Pro 15', 'sku' => 'NB-1500', 'quantity' => 10, 'price' => 1299.99]]);
InventoryItem::create(['encrypted_data' => ['name' => 'Monitor 27"', 'sku' => 'MON-27FHD', 'quantity' => 25, 'price' => 229.00]]);

// Stock movements
StockMovement::create(['encrypted_data' => ['inventory_item' => 'NB-1500', 'type' => 'in', 'quantity' => 10, 'note' => 'Erstlieferung']]);
StockMovement::create(['encrypted_data' => ['inventory_item' => 'MON-27FHD', 'type' => 'out', 'quantity' => 5, 'note' => 'Verkauf Filiale A']]);

// Cashbook entries
CashbookEntry::create(['encrypted_data' => ['entry_date' => now()->toDateString(), 'amount' => 1000.00, 'type' => 'income', 'category' => 'Startkapital']]);
CashbookEntry::create(['encrypted_data' => ['entry_date' => now()->toDateString(), 'amount' => 230.40, 'type' => 'expense', 'category' => 'Büromaterial']]);

// Vehicles
Vehicle::create(['encrypted_data' => ['identifier' => 'B-TAF 123', 'model' => 'Transporter T6', 'mileage' => 102340, 'active' => true]]);
Vehicle::create(['encrypted_data' => ['identifier' => 'B-TAF 456', 'model' => 'Caddy Cargo', 'mileage' => 80210, 'active' => true]]);

// Work schedules
WorkSchedule::create(['encrypted_data' => ['employee' => 'Max Mustermann', 'work_date' => now()->toDateString(), 'start_time' => '08:00', 'end_time' => '16:30', 'break_minutes' => 30]]);
WorkSchedule::create(['encrypted_data' => ['employee' => 'Erika Beispiel', 'work_date' => now()->addDay()->toDateString(), 'start_time' => '09:00', 'end_time' => '17:00', 'break_minutes' => 45]]);

// Documents
Document::create(['encrypted_data' => ['title' => 'Arbeitsvertrag Max', 'path' => 'storage/private/contracts/vertrag-max.pdf', 'category' => 'Personal']]);
Document::create(['encrypted_data' => ['title' => 'Fahrzeugbrief B-TAF 123', 'path' => 'storage/private/vehicles/brief-123.pdf', 'category' => 'Fahrzeuge']]);

// ---------------------------
// 3. Ergebnisse speichern
// ---------------------------
$data = [
    'employees'        => Employee::all()->toArray(),
    'customers'        => Customer::all()->toArray(),
    'inventory_items'  => InventoryItem::all()->toArray(),
    'stock_movements'  => StockMovement::all()->toArray(),
    'cashbook_entries' => CashbookEntry::all()->toArray(),
    'vehicles'         => Vehicle::all()->toArray(),
    'work_schedules'   => WorkSchedule::all()->toArray(),
    'documents'        => Document::all()->toArray(),
];

$file = $basePath . '/data-samples.json';
File::put($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Testdaten erstellt und gespeichert unter:\n{$file}\n";
echo "Hinweis: Skript dient nur Testzwecken – Dateien werden bei erneutem Lauf überschrieben.\n";
