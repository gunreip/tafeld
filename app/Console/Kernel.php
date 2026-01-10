<?php

namespace App\Console;

use App\Console\Commands\SplitCountries;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ProjectTreeCommand::class,
        \App\Console\Commands\ProjectWireNavigateCheck::class,
        \App\Console\Commands\ProjectGit::class,
        \App\Console\Commands\ProjectDatabase::class,
        \App\Console\Commands\ProjectBreezeKill::class,
        \App\Console\Commands\ProjectDataset::class,
        \App\Console\Commands\SplitCountries::class,    // php artisan countries:split
        \App\Console\Commands\ProjectBuild::class,
        \App\Console\Commands\ProjectVersions::class,
        \App\Console\Commands\AuditViewsComponents::class,  // php artisan audit:views-components
    ];
}
