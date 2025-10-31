## Schematische Darstellung der optimalen Projekt-Struktur

```bash
tafeld/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── CustomerController.php          # steuert Routen, Views, Logik
│   │   └── Requests/
│   │       └── CustomerRequest.php             # validierung der felder
│   └── View/
│       └── Components/
│           └── Customers/
│               ├── Form.php                    # logik der hauptformular-komponente
│               └── Inputs/                     # logik aller input-komponenten
│                   ├── FirstName.php
│                   ├── LastName.php
│                   ├── Salutation.php
│                   ├── Nationality.php
│                   ├── IdentityDocument.php
│                   └── ...
│
├── resources/
│   ├── views/
│   │   ├── customers/
│   │   │   └── create.blade.php                # seite „kundenerfassung“
│   │   └── components/
│   │       └── customers/
│   │           ├── form.blade.php              # hauptformular (wrapper)
│   │           ├── alerts.blade.php            # feedback / toasts
│   │           └── inputs/                     # blade-dateien für alle felder
│   │               ├── first_name.blade.php
│   │               ├── last_name.blade.php
│   │               ├── salutation.blade.php
│   │               ├── nationality.blade.php
│   │               ├── identity_document.blade.php
│   │               └── ...
│
├── routes/
│   └── web.php                                 # enthält Route::resource('customers', ...)
│
├── public/
│   └── storage/                                # symlink für uploads (z. B. Ausweis-Scans)
│
└── database/
    └── migrations/
        └── create_customers_table.php          # definiert alle felder der tabelle
```
