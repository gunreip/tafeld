<?php

namespace App\Console;

use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        \App\Console\Commands\ProjectTreeCommand::class,
        \App\Console\Commands\ProjectWireNavigateCheck::class,
        \App\Console\Commands\ProjectGit::class,
        \App\Console\Commands\ProjectDatabase::class,
        \App\Console\Commands\ProjectBreezeKill::class,
    ];
}
