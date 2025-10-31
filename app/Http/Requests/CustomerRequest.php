<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        // interne nutzung, keine rollenprüfung nötig
        return true;
    }

    public function rules(): array
    {
        return [
            'salutation'            => ['nullable', 'string', 'max:50'],
            'title'                 => ['nullable', 'string', 'max:50'],
            'customer_number'       => ['nullable', 'string', 'max:50'],
            'first_name'            => ['required', 'string', 'max:100'],
            'last_name'             => ['required', 'string', 'max:100'],
            'birth_name'            => ['nullable', 'string', 'max:100'],
            'birth_date'            => ['required', 'date', 'before:today'],
            'birth_country'         => ['nullable', 'string', 'max:100'],
            'birth_city'            => ['nullable', 'string', 'max:100'],
            'nationality'           => ['nullable', 'string', 'max:100'],
            'personalausweis_nummer' => ['nullable', 'string', 'max:50'],
            'personalausweis_dokument' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
            'familienstand'         => ['nullable', 'string', 'max:50'],
            'religion'              => ['nullable', 'string', 'max:100'],
            'familienvorstand'      => ['nullable', 'boolean'],
            'bedarfsgemeinschaftsnummer' => ['nullable', 'string', 'max:50'],
            'zustaendiges_amt'      => ['nullable', 'string', 'max:100'],
            'anzahl_personen'       => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Bitte den Vornamen angeben.',
            'last_name.required'  => 'Bitte den Nachnamen angeben.',
            'birth_date.before'   => 'Das Geburtsdatum darf nicht in der Zukunft liegen.',
            'personalausweis_dokument.mimes' => 'Nur JPG, PNG oder PDF erlaubt.',
            'personalausweis_dokument.max'   => 'Datei darf maximal 2 MB groß sein.',
        ];
    }
}
