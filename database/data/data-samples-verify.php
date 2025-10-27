<?php

// Vergleich verschlüsselter Inhalte – nur für Testzwecke
// Pfad: database/data/data-samples-verify.php
// Ausführung: include_once('database/data/data-samples-verify.php');         // Strukturprüfung

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
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

$basePath = database_path('data');
$file = $basePath . '/data-samples.json';

if (!File::exists($file)) {
    echo "Keine Vergleichsdatei gefunden: {$file}\n";
    return;
}

$expected = json_decode(File::get($file), true);
$current = [
    'employees'        => Employee::all()->toArray(),
    'customers'        => Customer::all()->toArray(),
    'inventory_items'  => InventoryItem::all()->toArray(),
    'stock_movements'  => StockMovement::all()->toArray(),
    'cashbook_entries' => CashbookEntry::all()->toArray(),
    'vehicles'         => Vehicle::all()->toArray(),
    'work_schedules'   => WorkSchedule::all()->toArray(),
    'documents'        => Document::all()->toArray(),
];

$summary = [];
foreach ($expected as $table => $exp) {
    $cur = $current[$table] ?? [];
    $summary[$table] = [
        'expected_count' => count($exp),
        'current_count'  => count($cur),
        'hash_match'     => false, // verschlüsselte Daten immer verschieden
    ];
}

$outFile = $basePath . '/data-samples-verify.json';
File::put($outFile, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Vergleich abgeschlossen (nur Testzwecke, Ciphertexte unterscheiden sich).\n";
echo "Ergebnis unter: {$outFile}\n";
