# Upgrade-Plan – Projekt tafeld

**Stand:** 2025-10-18 21:47:21

---

## 1. Rahmenbedingungen & Ziele
- Minimales Risiko, kontrollierte Updates in Staging-Umgebung.
- Ziel: Stabilität, Sicherheit, Kompatibilität mit Laravel 13 und PHP 8.4.
- Aktuell: Laravel 12.34 | PHP 8.3.6 | PostgreSQL 18.0 | Redis 7.0.15 | Ubuntu 24.04 (WSL2)

---

## 2. Upgrade-Zielmatrix

| Komponente         | Aktuell           | Zielversion         | Bemerkung                                                 |
| ------------------ | ----------------- | ------------------- | --------------------------------------------------------- |
| Laravel Framework  | 12.x              | 13.x (nach Release) | Anpassung von Middleware, Broadcasting, Queue‑Jobs prüfen |
| PHP                | 8.3               | 8.4                 | Alle Erweiterungen und Composer‑Pakete prüfen             |
| PostgreSQL         | 18.x              | 19.x                | Major‑Upgrade, Dump‑Restore oder `pg_upgrade` nutzen      |
| Redis              | 7.0               | 8.x                 | Falls verfügbar, vorher `RDB`‑Backup erzeugen             |
| Apache/Nginx       | 2.4 / 1.24        | 2.6 / 1.26          | Module‑Kompatibilität sicherstellen                       |
| Node/Tailwind/Vite | 22.20 / 4.1 / 7.0 | 23.x / 5.x / 8.x    | Build‑Pipeline und `vite.config.js` prüfen                |

---

## 3. Vorgehensweise

### Phase 1 – Vorbereitung
- Komplettes Backup (Code, Datenbanken, `.env`, Assets)
- Test‑/Staging‑Umgebung klonen
- `php artisan project:audit --with-project-structure --format=json` ausführen → Basiszustand dokumentieren
- `composer outdated`, `npm outdated` prüfen

### Phase 2 – System & Umgebung
- PHP 8.4 installieren → `php -v` prüfen
- Webserver aktualisieren, PHP‑FPM‑Socket testen
- Node 23 + NPM/Yarn aktualisieren

### Phase 3 – Datenbanken
- PostgreSQL: Dump → Upgrade → Restore (`pg_dumpall`, `pg_restore`)
- SQLite: keine Migration nötig, ggf. nur Prüfen
- Redis: `redis-check-rdb` und Rebuild nach Update

### Phase 4 – Framework & Code
- Laravel‑Upgrade‑Guide befolgen (`12.x`→`13.x`)
- `composer.json` Versionen anpassen, Autoloader regenerieren
- Unit‑Tests, Feature‑Tests, CI/CD‑Pipeline durchlaufen lassen

### Phase 5 – Frontend
- `npm install` → Build testen (`npm run build`)
- Tailwind, Livewire, Volt, Flux auf Kompatibilität prüfen
- Browser‑Tests und Lighthouse‑Check

### Phase 6 – Abschluss
- Neues `project:audit` ausführen und archivieren
- Ergebnisse in `docs/changelog.md` ergänzen
- Neues Release‑Tag setzen (`v1.x`)

---

## 4. Zeitschiene

| Zeitraum    | Ziel                          | Verantwortlich |
| ----------- | ----------------------------- | -------------- |
| 0–1 Monat   | Backups, Audit, Test‑Umgebung | DevOps         |
| 1–3 Monate  | System + DB‑Upgrade           | Sysadmin       |
| 3–6 Monate  | Laravel/PHP‑Upgrade, Tests    | Entwickler     |
| 6–9 Monate  | Frontend‑Upgrade              | Frontend‑Team  |
| 9–12 Monate | Stabilisierung, Monitoring    | Gesamtteam     |

---

## 5. Risiken & Gegenmaßnahmen
- **Paket‑Inkompatibilitäten** → frühzeitig `composer why-not` nutzen
- **Downtime bei DB‑Upgrade** → Wartungsfenster, Replikation oder Snapshot
- **Abhängigkeiten brechen Build** → separate Branches für Upgrade
- **Fehlende Dokumentation** → `docs/` regelmäßig aktualisieren

---

## 6. Nacharbeiten
- Auditberichte regelmäßig generieren
- Alte `.logs/` nach 90 Tagen rotieren
- `docs/upgrade-plan.md` bei jedem Schritt aktualisieren
