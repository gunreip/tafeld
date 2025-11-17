<?php

namespace App\Livewire\Pages\Persons;

use Livewire\Component;
use App\Models\Person;
use App\Models\Country;


class Edit extends Component
{
    public Person $person;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public array $countries = [];

    public function mount(Person $person)
    {
        $this->person = $person;

        $this->first_name = $person->first_name;
        $this->last_name  = $person->last_name;
        $this->email      = $person->email;
        $this->phone      = $person->phone;
        $this->countries  = Country::orderBy('name_en')->get()->toArray();
    }

    public function save()
    {
        $this->person->update([
            'first_name' => $this->first_name,
            'last_name'  => $this->last_name,
            'email'      => $this->email,
            'phone'      => $this->phone,
            'country_id' => $this->country_id ?: null,
        ]);

        return redirect()->route('persons.index');
    }

    public function render()
    {
        return view('livewire.pages.persons.edit')
            ->layout('livewire.layout.app');
    }
}
