<?php

// Klartext-Vergleich der entschlüsselten Daten – Testzweck
// Pfad: database/data/data-samples-verify-plaintext.php
// Ausführung: include_once('database/data/data-samples-verify-plaintext.php'); // Klartextprüfung

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
$inputFile  = $basePath . '/data-samples.json';
$outputFile = $basePath . '/data-samples-plaintext.json';
$summaryFile = $basePath . '/data-samples-verify-plaintext.json';

if (!File::exists($inputFile)) {
    echo "Datei nicht gefunden: {$inputFile}\n";
    return;
}

echo "TAFELD TESTMODUS – Klartext-Vergleich (Dateien werden überschrieben).\n";

$expected = json_decode(File::get($inputFile), true);
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

// --------------------------------------------
//  Hilfsfunktion (nur einmal definieren)
// --------------------------------------------
if (!function_exists('decryptRows')) {
    function decryptRows(array $rows): array
    {
        return array_map(function ($row) {
            $payload = $row['encrypted_data'] ?? null;
            try {
                $row['plaintext'] = $payload ? json_decode(\Illuminate\Support\Facades\Crypt::decryptString($payload), true) : [];
            } catch (Throwable $e) {
                $row['plaintext'] = ['error' => 'Entschlüsselung fehlgeschlagen'];
            }
            unset($row['encrypted_data']);
            return $row;
        }, $rows);
    }
}

$plaintextData = [];
$summary = [];

foreach ($current as $table => $rows) {
    $dec = decryptRows($rows);
    $plaintextData[$table] = $dec;

    $exp = $expected[$table] ?? [];
    $summary[$table] = [
        'expected_count' => count($exp),
        'current_count'  => count($dec),
        'count_match'    => count($exp) === count($dec),
    ];
}

File::put($outputFile, json_encode($plaintextData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
File::put($summaryFile, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo "Klartextprüfung abgeschlossen.\n";
echo " - Entschlüsselte Daten: {$outputFile}\n";
echo " - Zusammenfassung: {$summaryFile}\n";
echo "Dateien werden bei erneutem Lauf überschrieben (Testmodus).\n";
