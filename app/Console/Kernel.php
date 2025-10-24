<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Die Commands, die Laravel beim Booten registriert.
     */
    protected $commands = [
        // Eigene Commands hier registrieren:
        \App\Console\Commands\ProjectStructure::class,
        \App\Console\Commands\InitDocs::class,
        \App\Console\Commands\ProjectStructure::class,
        \App\Console\Commands\ProjectGitPush::class,
        \App\Console\Commands\ProjectArtisanDocs::class,
        \App\Console\Commands\ProjectAuditMain::class,
        \App\Console\Commands\ProjectStructureExtend::class,
        \App\Console\Commands\ProjectGitCheckout::class,
    ];

    /**
     * Schedule-Definition.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Beispiel: $schedule->command('inspire')->hourly();
    }

    /**
     * Registriert die Commands automatisch.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
