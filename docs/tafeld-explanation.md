# Erläuterung was wofür

| ebene                 | aufgabe                                               | enthält                                                 |
| --------------------- | ----------------------------------------------------- | ------------------------------------------------------- |
| **Controller**        | steuert routing, validierung und datenfluss           | `CustomerController`                                    |
| **Request**           | prüft felder (required, format, datei)                | `CustomerRequest`                                       |
| **View (create)**     | enthält das gesamte seitenlayout                      | `resources/views/customers/create.blade.php`            |
| **Component (Form)**  | kapselt `<form>`-struktur und bindet input-components | `Form.php` + `form.blade.php`                           |
| **Input-Komponenten** | definieren einzelne felder (label, input, fehler)     | `resources/views/components/customers/inputs/*`         |
| **Alert-Komponente**  | zeigt meldungen und toasts                            | `resources/views/components/customers/alerts.blade.php` |

!!! 🔍 4️⃣ mögliche inkonsistenzen
    typische fehlerquellen, wenn du zu wenige felder siehst:
    `form.blade.php` bindet nicht alle input-komponenten (z. B. fehlende `<x-customers.inputs.birth_country>`).
    dateinamen weichen von component-names ab (z. B. `_` statt `-` oder falsches *casing*).
    view-cache (`php artisan view:clear`) enthält alte versionen.
    namespace conflict – wenn du versehentlich eine komponente doppelt unter `app/View/Components` und `resources/views/components` hast.

!!! ✅ empfohlene prüfung
    `php artisan view:clear && php artisan optimize:clear`
    kontrollieren, dass `resources/views/components/customers/form.blade.php` wirklich alle `<x-customers.inputs.*>` auflistet.
    sicherstellen, dass **jeder** *komponenten-name* exakt dem *dateinamen* entspricht.