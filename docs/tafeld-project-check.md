# Projekt-Check

präzise erklärung:

---

!!! 🧩 1️⃣  welche dateien für welchen aufruf verwendet werden

    Laravel lädt Views und Components dynamisch nach festen konventionen.
    du kannst **genau nachverfolgen**, was bei einem aufruf (`/customers/create`) passiert, über:

    **a) artisan debug-befehle**

    ```bash
    php artisan route:list
    ```

    → zeigt dir alle definierten Routen, Methoden, Controller und View-Namen.

    Beispiel:

    ```
    | Method | URI              | Name             | Action                                         |
    | ------ | ---------------- | ---------------- | ---------------------------------------------- |
    | GET    | customers/create | customers.create | App\Http\Controllers\CustomerController@create |
    ```

    → so weißt du, welcher Controller bei welchem Aufruf ausgeführt wird.

    ---

    **b) Controller-Nachverfolgung**

    Im Controller (`CustomerController@create`) kannst du kurzfristig prüfen:

    ```php
    dump(__METHOD__); // zeigt den Methodennamen
    dump(view()->getName()); // gibt den Viewnamen zurück
    ```

    oder temporär einfügen:

    ```php
    logger()->info('View loaded:', ['view' => 'customers.create']);
    ```

    → erscheint in `storage/logs/laravel.log`.

    ---

    **c) View-Trace aktivieren**

    mit:

    ```bash
    php artisan view:clear
    php artisan view:cache
    ```

    werden alle verwendeten Views neu kompiliert.
    du kannst danach unter
    `storage/framework/views/`
    die **kompilierten blade-dateien** sehen → jede Datei trägt im Header den Ursprungs-Pfad:

    ```php
    /**PATH /home/gunreip/code/tafeld/resources/views/customers/create.blade.php ENDPATH**/
    ```

    → daran erkennst du, welche Quelldateien Laravel beim Rendern verwendet hat.

---

!!! 🧩 2️⃣  doppelte dateien finden

    **a) simple shell-suche (linux/wsl)**

    ```bash
    cd ~/code/tafeld
    find . -type f -name 'form.blade.php'
    find . -type f -name 'create.blade.php'
    ```

    → zeigt dir, ob du versehentlich mehrere versionen im projekt hast.

    oder alle doppelten components:

    ```bash
    find resources/views/components -type f | sort | uniq -d
    ```

    **b) artisan-komponentenliste**

    ```bash
    php artisan view:components
    ```

    zeigt alle registrierten komponenten (nur Laravel >=10.12).
    → du erkennst, ob z. B. `customers.form` doppelt registriert ist.

    ---

    **c) vollständiger Cache-Reset**

    nach jeder aufräumaktion:

    ```bash
    php artisan optimize:clear
    php artisan view:clear
    php artisan route:clear
    php artisan config:clear
    ```

    → zwingt Laravel, alle komponenten und views frisch zu laden.

---

!!! 🧭 3️⃣  praktische prüfung auf doppelte komponenten

    Ein einfacher grep:

    ```bash
    grep -R "x-customers.inputs" resources/views/components/customers/form.blade.php
    ```

    → zeigt dir alle eingebundenen Komponenten.
    Wenn du sie mit den tatsächlichen Dateien im Ordner vergleichst:

    ```bash
    ls resources/views/components/customers/inputs/
    ```

    erkennst du sofort, ob eine Datei fehlt oder doppelt ist.

---

