<?php

// tafeld/app/Livewire/Pages/Persons/Create.php

namespace App\Livewire\Pages\Persons;

use Livewire\Component;
use App\Models\Person;
use App\Models\Country;
use App\Support\Breadcrumbs;

class Create extends Component
{
    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $country_id = null;

    public function save()
    {
        Person::create([
            'first_name'  => $this->first_name,
            'last_name'   => $this->last_name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'country_id'  => $this->country_id,
        ]);

        return redirect()->route('persons.index');
    }

    public function render()
    {
        $countries = Country::orderBy('name_de')->get();

        return view('livewire.pages.persons.create', [
            'countries' => Country::orderBy('name_de')->get(),
        ])->layout('livewire.layout.app', [
            'breadcrumbs' => Breadcrumbs::for('persons.create'),
            'tafelName'   => 'Tafel Wesseling e.V.',
            'avatarUrl'   => '/images/avatars/default-user.svg',
        ]);
    }
}
