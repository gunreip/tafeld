# Schematischer Ablauf eines Seitenaufrufs

```bash
┌───────────────────────────────┐
│ Browser: /customers/create    │
└──────────────┬────────────────┘
               │
               ▼
       routes/web.php
       → GET /customers/create
               │
               ▼
   app/Http/Controllers/CustomerController@create
               │
               ▼
  resources/views/customers/create.blade.php
     enthält: 
     - <x-customers.alerts />
     - <x-customers.form />
               │
               ▼
  app/View/Components/Customers/Form.php
     lädt View:
     resources/views/components/customers/form.blade.php
               │
               ▼
  form.blade.php
     bindet:
     - einzelne Input-Komponenten (x-customers.inputs.*)
               │
               ▼
  resources/views/components/customers/inputs/*.blade.php
     individuelle Felder + Fehlermeldungen
               │
               ▼
   Laravel-Rendering → HTML-Seite im Browser
```
