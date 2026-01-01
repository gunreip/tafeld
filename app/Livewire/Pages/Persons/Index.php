<?php

// tafeld/app/Livewire/Pages/Persons/Index.php

namespace App\Livewire\Pages\Persons;

use Livewire\Component;
use App\Models\Person;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'last_name';
    public $sortDirection = 'asc';

    // render
    public function render()
    {
        $people = Person::query()
            ->with('country')
            ->when(
                $this->search,
                fn($q) =>
                $q->where(function ($query) {
                    $query->where('first_name', 'ILIKE', "%{$this->search}%")
                        ->orWhere('last_name', 'ILIKE', "%{$this->search}%")
                        ->orWhere('email', 'ILIKE', "%{$this->search}%")
                        ->orWhere('phone', 'ILIKE', "%{$this->search}%");
                })
            )
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);

        \Log::info('Countries loaded for first person', [
            'person_id'    => optional($people->first())->id,
            'country_id'   => optional($people->first())->country_id,
            'country_name' => optional(optional($people->first())->country)->name_de,
        ]);

        return view('livewire.pages.persons.index', [
            'people' => $people,
        ])->layout('livewire.layout.app');
    }

    // delete
    public function delete(string $id)
    {
        $person = \App\Models\Person::findOrFail($id);
        $person->delete();

        // Optional: Audit protokollieren
        \Illuminate\Support\Facades\Log::info('Person deleted', ['id' => $id]);

        session()->flash('message', 'Person erfolgreich gelöscht.');
    }

    // sortBy
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
}
