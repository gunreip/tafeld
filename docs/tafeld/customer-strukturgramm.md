# grafische und logisch-technische darstellung des kundenformular-systems (*tafeld/customers*).

sie zeigt klar den Daten- und Aufruf-Fluss sowie die Strukturhierarchie deiner Komponenten.

---

## 🧩 1️⃣ Strukturdiagramm – Komponentenebenen

```text
resources/views/customers/create.blade.php
│
└── <x-app-layout>
    │
    ├── <x-customers.alerts>            → Feedback & Toasts
    │
    └── <x-customers.form>              → Hauptformular
         │
         ├── Abschnitt 1: Persönliche Daten
         │    ├── salutation
         │    ├── title
         │    ├── customer-number
         │    ├── first-name
         │    ├── last-name
         │    ├── birth-name
         │    ├── birth-date
         │    ├── birth-country
         │    ├── birth-city
         │    └── nationality
         │
         ├── Abschnitt 2: Ausweis- & Familiendaten
         │    ├── identity-document
         │    ├── family-status
         │    ├── religion
         │    ├── household-number
         │    ├── responsible-office
         │    └── household-person-count
         │
         └── Abschnitt 3: Status & Verwaltung
              ├── valid-until
              ├── customer-day
              ├── customer-day-preferred
              ├── income
              └── customer-category
```

---

## 🧭 2️⃣ Ablaufdiagramm – vom Aufruf bis zur Ausgabe

```text
Browser:  http://127.0.0.1:8000/customers/create
│
▼
Route:  GET /customers/create
       → defined in routes/web.php
│
▼
Controller:  CustomerController@create
       → returns view('customers.create')
│
▼
View:  resources/views/customers/create.blade.php
       ├── <x-app-layout>               (Layout)
       ├── <x-customers.alerts>         (Session-/Validation-Messages)
       └── <x-customers.form>           (Hauptformular)
│
▼
Component Logic:  app/View/Components/Customers/Form.php
       → loads view('components.customers.form')
│
▼
View:  resources/views/components/customers/form.blade.php
       → includes all input-components (x-customers.inputs.*)
│
▼
Components (Inputs)
       → resources/views/components/customers/inputs/*.blade.php
       → render each field (label, input, icon, error)
│
▼
Validation:  app/Http/Requests/CustomerRequest.php
       → triggered on POST /customers
│
▼
Controller:  CustomerController@store
       → validates data → creates new Customer → redirect back
│
▼
Feedback:  session('success' / 'error')
       → displayed via <x-customers.alerts>
│
▼
Result:  rendered HTML page with toast + inline-feedback
```

---

## 🧱 3️⃣ Datenfluss-Logik

```text
[Input-Components]
   ↓ (submit)
[CustomerRequest]
   ↓ (validated data)
[CustomerController@store]
   ↓ (create record)
[Model: Customer]
   ↓ (save to DB)
[Redirect → session('success')]
   ↓
[x-customers.alerts] → Inline + Toast Feedback
```

---

## 🧮 4️⃣ Speicher- und Ressourcenbeziehungen

| Quelle                                                       | Ziel                                           | Beschreibung |
| ------------------------------------------------------------ | ---------------------------------------------- | ------------ |
| **resources/views/customers/create.blade.php**               | Einstiegspunkt für die Kundenmaske             |              |
| **app/View/Components/Customers/Form.php**                   | Logik für Props `action`, `method`, `customer` |              |
| **resources/views/components/customers/form.blade.php**      | Wrapper für alle Eingabekomponenten            |              |
| **resources/views/components/customers/inputs/\*.blade.php** | Einzelkomponenten, 1 Feld = 1 Datei            |              |
| **app/Http/Requests/CustomerRequest.php**                    | Validierung und Fehlermeldungen                |              |
| **app/Http/Controllers/CustomerController.php**              | Steuerung, Speichern, Redirect                 |              |
| **database/migrations/create_customers_table.php**           | Datenbankfelder passend zu Inputs              |              |
| **storage/app/public/uploads/customers/**                    | Upload-Ziel für Dokumente (z. B. Ausweise)     |              |

---

## 🧾 5️⃣ Gesamtüberblick (Schema)

```
Browser UI
   ↓
View (create)
   ↓
<x-customers.form>
   ↓
inputs + validation feedback
   ↓
CustomerRequest → Controller → Model → DB
   ↓
<x-customers.alerts> (success/error)
   ↓
Rendered HTML Response
```

---

Dieses Diagramm bildet die **komplette Funktionskette** deines Formularsystems ab – von der Route bis zur Datenbank.

Damit lässt sich leicht prüfen, wo Fehler oder doppelte Komponenten auftreten könnten.

---
