Hier ist der exakte Inhalt für
`/home/gunreip/code/tafeld/docs/tinker_commands.md`
(sicherer direkt speicherbar):

# Tinker Commands – tafeld Projekt

Alle Verwaltungs-, Datenbank- und Entwicklungsaufgaben werden **innerhalb des Laravel-Kontexts**
ausgeführt – ausschließlich über `php artisan tinker`.

---

## 1. Grundlagen

### Start

```bash
php artisan tinker
```

### Hilfe

```php
help
```

---

## 2. Tabellen anzeigen

```php
\DB::select('SELECT tablename FROM pg_tables WHERE schemaname = ?', ['public']);
```

---

## 3. Datensätze zählen

```php
\DB::table('employees')->count();
\DB::table('customers')->count();
\DB::table('documents')->count();
```

---

## 4. Beispieldaten prüfen

```php
\DB::table('documents')->select('id','encrypted_data')->limit(3)->get();
```

---

## 5. Seeder ausführen

Einzelner Seeder:

```php
Artisan::call('db:seed', ['--class' => 'DocumentSeeder']);
Artisan::output();
```

Alle Seeder:

```php
Artisan::call('db:seed');
Artisan::output();
```

---

## 6. Neuer Datensatz (verschlüsselt) anlegen

```php
use Illuminate\Support\Str;

\DB::table('documents')->insert([
    'uuid' => Str::uuid(),
    'title' => 'Vertrag Demo',
    'path' => 'storage/private/contracts/demo.pdf',
    'user_id' => 1,
    'encrypted_data' => encrypt(json_encode(['info' => 'intern'])),
    'created_at' => now(),
    'updated_at' => now(),
]);
```

---

## 7. Audit-Log prüfen

```php
$logfile = storage_path('logs/audit/' . now()->format('Ymd') . '.jsonl');
echo file_exists($logfile)
    ? implode("\n", array_slice(file($logfile), -5))
    : 'Keine Audit-Einträge vorhanden';
```

---

## 8. Migrationen & Status

```php
Artisan::call('migrate:status');
Artisan::output();
```

---

## 9. Migrationen ausführen oder zurücksetzen

```php
Artisan::call('migrate:fresh', ['--seed' => true]);
Artisan::output();
```

---

## 10. Factory (später für Tests)

```php
\App\Models\User::factory()->count(3)->create();
```

---

## 11. Informationen zur Umgebung

```php
config('app.env');
config('database.connections.pgsql.database');
```

---

## 12. Benutzer-Check

```php
\App\Models\User::all(['id','name','email']);
```

---

## 13. Schnellprüfung aller Tabellen mit Datensätzen

```php
collect(\DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema='public'"))
    ->pluck('table_name')
    ->each(fn($t) => print("$t: " . \DB::table($t)->count() . " rows\n"));
```

---

> **Hinweis:**
Alle Einträge bleiben verschlüsselt in `encrypted_data`.
Nur `COUNT(*)` oder JSON-Längen prüfen, nie Inhalte im Klartext.

---

> Version 1.0 · erstellt automatisch am 2025-10-26

---

Datei einfach anlegen:
```bash
mkdir -p /home/gunreip/code/tafeld/docs
nano /home/gunreip/code/tafeld/docs/tinker_commands.md
```

und den obigen Inhalt einfügen.

Ab jetzt gelten Tinker-Operationen als Standard-Workflow für alle DB-, Seeder-, Audit- oder Model-Aktionen.

---
