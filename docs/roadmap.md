# ROADMAP.md – Projekt **tafeld**

**Status:** Basisinstallation (Laravel 12.34 / PHP 8.3.6 / PostgreSQL 18 / Node 22 / lokal unter WSL 2)
**Ziel:** internes, nicht-öffentliches CMS für Benutzer-, Kunden-, Waren-, Finanz- und Personalverwaltung
**Priorität:** Sicherheit, Nachvollziehbarkeit, vollständige Verschlüsselung personenbezogener Daten

---

## 1. Fundamentphase – Infrastruktur & Sicherheit

**Ziel:** stabile, verschlüsselte, nachvollziehbare Systembasis.

- `.env` absichern → lokale Keys, eigene Logging-Pfade, keine externen DB-Verbindungen
- `laravel/fortify` + `livewire` aktivieren → Login / 2-Faktor-Authentifizierung
- zentrale **Audit-Middleware** implementieren → alle Requests als JSONL protokollieren
- Encryption-Service (OpenSSL, symmetrisch + asymmetrisch) für personenbezogene Felder
- tägliche verschlüsselte Backups über `schedule:run` → `storage/backups`

---

## 2. Datenstrukturphase – Entitäten & Migrationen

**Ziel:** definierte Modelle und Migrationslogik.

Model-/Migration-Paare:
- `User`, `Employee`, `Customer`, `InventoryItem`, `StockMovement`, `CashbookEntry`, `Vehicle`, `WorkSchedule`, `Document`

Weitere Punkte:
- Repositories / Services zur Trennung von Business-Logik
- Seeders für Test-/Beispieldaten
- Mutatoren für Verschlüsselung sensitiver Spalten

---

## 3. Kernlogikphase – Module & Prozesse

**Ziel:** funktionale Grundmodule.

- Benutzerverwaltung (Fortify + Roles/Permissions)
- Kunden- / Dokumentenmanagement mit Dateiupload (`storage/private`, verschlüsselt)
- Waren-, Bestands- und Kassenbuch-Module mit Artisan-Subcommands
  - `php artisan tafeld:inventory:add`
  - `php artisan tafeld:cash:report`
- Ereignis-Logging über `monolog` + eigene Audit-Handler
- optionale lokale API-Layer

---

## 4. Interaktionsphase – UI & Bedienung

**Ziel:** funktionales Frontend (Design zweitrangig).

- Livewire 3 + Tailwind 4.1 einbinden
- Layoutbasis: `resources/views/layouts/base.blade.php`
- CRUD-Interfaces für alle Hauptmodelle
- Audit-Anzeige mit Filterung nach Benutzer, Modul, Zeitraum

---

## 5. Auswertungs- / Reportingphase

**Ziel:** vollständige Transparenz über Systemaktionen.

- `artisan tafeld:audit:report` → generiert lokale HTML/CSV-Reports
- Filter für Zeitraum, Benutzer, Modul, Fehlerarten
- Statistiken: Top-Kunden, Warenbewegungen, Fehlversuche u. a.

---

## 6. Wartungs- & Langzeitphase

**Ziel:** Stabilität und Dauerbetrieb.

- Git-Tagging für alle Meilensteine
- monatliche Automatik-Audits (`php artisan tafeld:audit:run`)
- Systemdienst (`systemd` oder crontab) für Cleanup / Backups

---

## Empfohlene Reihenfolge

1. Sicherheit & Verschlüsselung
2. Datenmodelle + Migrationen
3. Artisan-Kernkommandos
4. Livewire / Tailwind UI
5. Reporting / Audits
6. Langzeitbetrieb & Backups

---

## Projektprinzipien

- **Nur lokaler Betrieb** → keine externen Zugriffe oder Cloud-Dienste
- **Alles über Artisan** → Installation, Migration, Wartung, Auswertung
- **Jede Aktion protokollieren** → einheitliche Audit-Pipeline
- **Strikte Trennung von Code und Daten**
- **Verschlüsselung auf Feld-Ebene** für personenbezogene Informationen

---

> Version 0.1 · Erstellt automatisch aus System-Audit (`tafeld-audit.json`, 2025-10-26)

---