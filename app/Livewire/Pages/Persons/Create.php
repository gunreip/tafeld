<?php

// tafeld/app/Livewire/Pages/Persons/Create.php

namespace App\Livewire\Pages\Persons;

use Livewire\Component;
use App\Models\Person;
use App\Models\Country;
use App\Support\Breadcrumbs;
use App\Enums\CountryWorkArea;
use App\Support\TafeldDebug\Debug;

class Create extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Formularfelder (müssen exakt der View entsprechen,
    | Speicherung folgt erst nach Migrationserweiterung)
    |--------------------------------------------------------------------------
    */

    // Personendaten
    public $first_name = '';
    public $last_name = '';

    public $first_name_sort_key = null;
    public $first_name_translit = null;
    public $last_name_sort_key = null;
    public $last_name_translit = null;

    // Zusätzliche Personenstammdaten (aus View)
    public $salutation = 'frau';
    public $title = '';
    public $personal_number = '';

    // Adresse
    public $street = '';
    public $house_number = '';
    public $country_id = null;        // Wohnsitzland (FK)
    public $zipcode = '';
    public $city = '';

    // UI-ISO-Ländercode (nicht in DB gespeichert)
    public $iso3166_3 = 'DEU';

    // Staatsangehörigkeit
    public $nationality_id = null;    // FK → countries.id
    public $nationality = null;       // View-Property

    // WorkArea
    public ?CountryWorkArea $nationality_work_area = null;

    // E-Mail
    public $email_local = '';
    public $email_domain = '';

    // Telefon (Mobil)
    public $mobile_country_id = null; // FK → countries.id
    public $mobile_area = '';
    public $mobile_number = '';
    public $mobile_country = '+49';   // Sichtbarer String-Code

    // Telefon (Festnetz)
    public $phone_country_id = null;  // FK → countries.id
    public $phone_area = '';
    public $phone_number = '';
    public $landline_country = '+49'; // +49, +43, etc. (View)
    public $landline_area = '';
    public $landline_number = '';

    // Geburts- und Beschäftigungsdaten
    public $birthdate = null;
    public $employment_start = null;
    public $employment_end = null;

    // Arbeitserlaubnis
    public $work_permit = false;
    public $work_permit_until = null;

    // Feiertage für Datepicker (Key = YYYY-MM-DD)
    public array $holidays = [];

    /*
    |--------------------------------------------------------------------------
    | Initialwerte
    |--------------------------------------------------------------------------
    */
    public function mount()
    {
        Debug::log(
            'smoke.env',
            'ENV gate test'
        );

        Debug::log('smoke.global', 'Global enabled');
        Debug::log('smoke.run', 'A');
        Debug::log('smoke.kill', 'Kill now');
        Debug::log('smoke.after', 'MUSS unterdrückt werden');

        // Debug::log('smoke.discovery', 'First call');
        // Debug::log('smoke.discovery', 'Second call');
        // Debug::log('smoke.discovery', 'Scope enabled');
        // Debug::log('smoke.parent.child', 'Hierarchy test');
        Debug::log('smoke.run', 'B');
        // Debug::log('smoke.run', 'C');
        // Debug::log('smoke.run', 'D');
        // Debug::log('smoke.run', 'E');
        // Debug::log('smoke.fail', 'DB missing');

        // Standard-Staatsangehörigkeit: Deutschland
        $this->nationality_id = Country::where('iso_3166_2', 'DE')->value('id');

        // View-Property "nationality" auf denselben Wert setzen
        $this->nationality = $this->nationality_id;

        // WorkArea beim Initialisieren setzen
        $country = Country::find($this->nationality_id);
        $this->nationality_work_area = $country?->work_area
            ? CountryWorkArea::from($country->work_area)
            : null;

        // EU/EWR/CH → AE nicht erforderlich
        if ($this->nationality_work_area === CountryWorkArea::EU_EEA_SWISS) {
            $this->work_permit = true;
            $this->work_permit_until = null;
        }

        // Standard-Telefon-Land (FK): Deutschland
        $de = Country::where('iso_3166_2', 'DE')->value('id');
        $this->mobile_country_id = $de;
        $this->phone_country_id  = $de;

        // Sichtbare Ländercodes (UI-Felder)
        $this->mobile_country   = '+49';
        $this->landline_country = '+49';

        // Wohnsitzland optional auf DE setzen
        $this->country_id = $de;

        // UI-Feld: ISO3166-3
        $this->iso3166_3 = 'DEU';

        // Holiday-Loading ------------------------------------------------------------------
        $holidayBase = [];

        foreach (\App\Models\Holiday::where('country_id', $this->country_id)->orderBy('date')->get() as $h) {
            $key = $h->date->format('Y-m-d');
            $holidayBase[$key][] = [
                'type'         => 'holiday',
                'name_de'      => $h->name_de,
                'display_date' => $h->display_date,
                'confession'   => $h->confession,
            ];
        }

        // School-Events ---------------------------------------------------------------------
        $schoolDays = [];

        foreach (\App\Models\Event::where('event_type', \App\Enums\EventType::School)->get() as $event) {
            if (! $event->start_date || ! $event->end_date) {
                continue;
            }

            $cursor = $event->start_date->copy();
            $end    = $event->end_date->copy();

            while ($cursor->lte($end)) {
                $key = $cursor->format('Y-m-d');
                $schoolDays[$key][] = [
                    'type'         => 'school',
                    'name_de'      => $event->name_de,
                    'display_date' => true,
                    'confession'   => null,
                ];
                $cursor->addDay();
            }
        }

        // Merge -------------------------------------------------------------------------------
        $merged = [];

        foreach ($holidayBase as $day => $items) {
            foreach ($items as $obj) {
                $merged[$day][] = $obj;
            }
        }

        foreach ($schoolDays as $day => $items) {
            foreach ($items as $obj) {
                $merged[$day][] = $obj;
            }
        }

        // Duplikate entfernen -----------------------------------------------------------------
        foreach ($merged as $day => $items) {
            $unique = [];
            foreach ($items as $obj) {
                $hash = $obj['type'] . '|' . $obj['name_de'];
                $unique[$hash] = $obj;
            }
            $merged[$day] = array_values($unique);
        }

        $this->holidays = $merged;
    }

    /*
    |--------------------------------------------------------------------------
    | Dynamisches Update der Staatsangehörigkeit
    |--------------------------------------------------------------------------
    */
    public function updatedNationality($id)
    {
        $this->nationality_id = $id;

        $country = Country::find($id);
        $this->nationality_work_area = $country?->work_area
            ? CountryWorkArea::from($country->work_area)
            : null;

        if ($this->nationality_work_area === CountryWorkArea::EU_EEA_SWISS) {
            $this->work_permit       = true;
            $this->work_permit_until = null;
        } else {
            $this->work_permit       = false;
            $this->work_permit_until = null;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Speichern
    |--------------------------------------------------------------------------
    */
    public function save()
    {
        Person::create([
            'first_name'             => $this->first_name,
            'last_name'              => $this->last_name,
            'first_name_sort_key'    => $this->first_name_sort_key,
            'first_name_translit'    => $this->first_name_translit,
            'last_name_sort_key'     => $this->last_name_sort_key,
            'last_name_translit'     => $this->last_name_translit,
            'street'                 => $this->street,
            'house_number'           => $this->house_number,
            'country_id'             => $this->country_id,
            'zipcode'                => $this->zipcode,
            'city'                   => $this->city,
            'nationality_id'         => $this->nationality_id,
            'birthdate'              => $this->birthdate,
            'employment_start'       => $this->employment_start,
            'employment_end'         => $this->employment_end,
            'mobile_country_id'      => $this->mobile_country_id,
            'mobile_area'            => $this->mobile_area,
            'mobile_number'          => $this->mobile_number,
            'phone_country_id'       => $this->phone_country_id,
            'phone_area'             => $this->phone_area,
            'phone_number'           => $this->phone_number,
            'email_local'            => $this->email_local,
            'email_domain'           => $this->email_domain,
        ]);

        return redirect()->route('persons.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        return view(
            'livewire.pages.persons.create',
            [
                'countries' => Country::orderBy('sort_key_de')->get(),
                'holidays'  => $this->holidays,
            ]
        )->layout(
            'livewire.layout.app',
            [
                'breadcrumbs' => Breadcrumbs::for('persons.create'),
                'tafelName'   => 'Tafel Wesseling e. V.',
                'avatarUrl'   => '/images/avatars/default-user.svg',
            ]
        );
    }
}
