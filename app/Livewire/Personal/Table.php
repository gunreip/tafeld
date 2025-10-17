<?php

namespace App\Livewire\Personal;

use Livewire\Component;
use App\Models\Personal;

class Table extends Component
{
    public $personals;
    public $showModal = false;
    public $deleteModal = false;
    public $editId = null;

    public $name;
    public $position;
    public $abteilung;
    public $eintrittsdatum;
    public $aktiv = true;

    protected $rules = [
        'name' => 'required|string|max:255',
        'position' => 'nullable|string|max:255',
        'abteilung' => 'nullable|string|max:255',
        'eintrittsdatum' => 'nullable|date',
        'aktiv' => 'boolean',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->personals = Personal::orderBy('name')->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $record = Personal::findOrFail($id);
        $this->editId = $id;
        $this->name = $record->name;
        $this->position = $record->position;
        $this->abteilung = $record->abteilung;
        $this->eintrittsdatum = $record->eintrittsdatum;
        $this->aktiv = $record->aktiv;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        Personal::updateOrCreate(
            ['id' => $this->editId],
            [
                'name' => $this->name,
                'position' => $this->position,
                'abteilung' => $this->abteilung,
                'eintrittsdatum' => $this->eintrittsdatum,
                'aktiv' => $this->aktiv,
            ]
        );

        $this->resetForm();
        $this->showModal = false;
        $this->loadData();
    }

    public function confirmDelete($id)
    {
        $this->editId = $id;
        $this->deleteModal = true;
    }

    public function delete()
    {
        Personal::find($this->editId)?->delete();
        $this->deleteModal = false;
        $this->loadData();
    }

    private function resetForm()
    {
        $this->reset(['editId', 'name', 'position', 'abteilung', 'eintrittsdatum', 'aktiv']);
    }

    public function render()
    {
        return view('livewire.personal.table');
    }
}
