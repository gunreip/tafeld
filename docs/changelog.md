# Changelog

Änderungen, Versionen, Datum.

## [0.1.0] – Initiale Projektstruktur
- Start des Laravel-Projekts `tafeld`.
- Definition der Zielstruktur für Dokumentation und interne CLI-Tools.
- Einführung einer klaren Trennung zwischen Quellcode und generierten Logs unter `.logs/structure/`.

---

## [0.2.0] – Dokumentationssystem (`InitDocs`)
- Neues Artisan-Command: `project:init-docs`
  - Erstellt automatisch die Verzeichnisstruktur `docs/`.
  - Legt Standarddateien an:
    - `project.md`
    - `setup.md`
    - `structure.md`
    - `config.md`
    - `routes.md`
    - `commands.md`
    - `changelog.md`
  - Überspringt vorhandene Dateien.
- Dokumentationsverzeichnis wird versioniert und dient als dauerhafte Referenz des Projektstands.

---

## [0.3.0] – Projektstruktur-Ausgabe (`ProjectStructure`)
- Neues Artisan-Command: `project:structure`
  - Erstellt Dateistruktur-Ausgabe in `.logs/structure/`.
  - Standardformat: Text (`.txt`).
  - Option `--dir=<path>` zur gezielten Ausgabe einzelner Unterverzeichnisse.
  - Liest Projektnamen automatisch aus `.env` → `PROJ_NAME`.
  - Ignoriert `vendor` und `node_modules` außer auf Modulebene.

---

## [0.4.0] – Erweiterung: HTML- und JSON-Ausgabe
- `project:structure` erweitert um Option `--format=txt|html|json`.
- HTML-Ausgabe mit einfacher CSS-Struktur und verschachtelter Listenansicht.
- JSON-Ausgabe im strukturierten Baumformat (`type: dir|file`).
- Vereinheitlichte Dateinamen: `<project>-struct[-<dir>].<format>`.

---

## [0.4.1] – Kernel-Integration und Wiederherstellung
- Anleitung zur korrekten Rekonstruktion von `app/Console/Kernel.php`.
- Sicherstellung, dass alle Commands (`InitDocs`, `ProjectStructure`) zentral registriert sind.
- Validierte Kompatibilität mit Standard-Laravel-Bootprozess.

---

## [0.5.0] – Projekt-Audit (`project:audit`)
- Neues Artisan-Command: `project:audit`
  - Erstellt einen umfassenden Audit-Bericht mit System- und Umgebungsdaten.
  - Unterstützt Ausgabeformate: `--format=md|html|json`.
  - Option `--with-project-structure`: integriert die Ausgabe des `project:structure`-Commands.
  - Standard-Ausgabe unter `.logs/audits/<project>-audit.<format>`.

---

## [0.6.0] – Erweiterter Audit mit Paket- und Systemanalyse
- Audit erweitert um:
  - Alle Datenbankverbindungen und Versionen (über `psql --version`, `mysql --version`, etc.).
  - PHP-Erweiterungen.
  - Composer- und Node-Abhängigkeiten (Frontend- und Core-Bereich).
  - Automatische Referenzierung in `docs/structure.md`.
- Markdown, HTML und JSON strukturiert um Tabellen und Codeblöcke ergänzt.

---

## [0.7.0] – System-Enhanced Audit
- `project:audit` neu implementiert mit direkter System- und Servererkennung:
  - OS, Kernel, PHP, Node, Composer-Version.
  - Datenbankerkennung über CLI (`psql`, `mysql`, `sqlite3`, `redis-server`, `mongod`).
  - Webserver-Erkennung (`apache2`, `nginx`, `caddy`).
- Einheitliche JSON-, HTML- und Markdown-Ausgabe.
- Ziel: präziser Status-Quo-Abgleich mit produktiver Infrastruktur.

---

## [0.8.0] – Upgrade-Plan
- Neuer generierter Bericht `docs/upgrade-plan.md`.
- Enthält Roadmap für Laravel 13, PHP 8.4, PostgreSQL 19, Tailwind 5 und Vite 8.
- Detaillierte Ablauf- und Zeitplanung (0–12 Monate).
- Risiken, Gegenmaßnahmen und Nacharbeitsplan integriert.

---


- Git Push [2025-10-19 08:48:46](.logs/audits/git/git.html#run-20251019-084846) → https://github.com/gunreip/tafeld.git [main] : Initial push
- Git Push [2025-10-19 10:00:34](.logs/audits/git/git.html#run-20251019-100034) → https://github.com/gunreip/tafeld.git [main] : Upgrade Command ProjectGitPush
