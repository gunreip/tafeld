<?php

// tafeld/app/Livewire/DebugTest.php

namespace App\Livewire;

use Livewire\Component;

class DebugTest extends Component
{
    public function render()
    {
        // Debug-Ping auslösen
        $dbg = new \App\Support\TafeldDebug\Engine();
        $dbg->log(
            'persons.create.nationality',
            'DebugTest component render() fired',
            ['component' => 'DebugTest']
        );

        // Zweiter Scope: persons.create.workarea
        $dbg->log(
            'persons.create.workarea',
            'DebugTest workarea scope fired',
            ['component' => 'DebugTest']
        );


        $dbg->info('persons.create.nationality', 'Telescope-Integration getestet', [
            'component' => 'DebugTest',
        ]);


        // Livewire 3 Browser-Event senden (direkt aus der Komponente)
        $this->dispatch(
            'tafeld-debug',
            scope: 'persons.create.nationality',
            message: 'DebugTest component render() fired',
            context: ['component' => 'DebugTest']
        );

        // Zweites Browser-Event für den Workarea-Scope
        $this->dispatch(
            'tafeld-debug',
            scope: 'persons.create.workarea',
            message: 'DebugTest workarea scope fired',
            context: ['component' => 'DebugTest']
        );

        return view('livewire.debug-test')
            ->layout('livewire.layout.app');
    }
}
