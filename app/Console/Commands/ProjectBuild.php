<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ProjectBuild extends Command
{
    protected $signature = 'project:build';
    protected $description = 'Clear caches and run frontend build';

    public function handle(): int
    {
        $this->info('Clearing Laravel caches…');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('view:clear');
        $this->call('route:clear');
        $this->call('optimize:clear');

        $this->info('Running NPM build…');
        exec('npm run build');

        $this->info('Build complete.');
        return Command::SUCCESS;
    }
}
