<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class ProjectGit extends Command
{
    protected $signature = 'project:git {action?} {arg?} {--list}';
    protected $description = 'Git-Steuerung mit JSON-Audits. Nutze --list für Übersicht.';

    public function handle()
    {
        if ($this->option('list')) {
            $this->line($this->listActions());
            return 0;
        }

        $action = $this->argument('action');
        $arg    = $this->argument('arg');

        $cmd = $this->buildCommand($action, $arg);

        if (!$cmd) {
            $this->error("Unknown git action: $action");
            return 1;
        }

        $this->info("Running: $cmd");

        $process = Process::fromShellCommandline($cmd, base_path());
        $process->run();

        $success = $process->isSuccessful();

        $output = $process->getOutput();
        $error  = $process->getErrorOutput();

        $this->writeAudit($cmd, $success, $output, $error);

        $this->line($output);

        return $success ? 0 : 1;
    }

    protected function listActions()
    {
        return <<<TXT
Verfügbare Git-Aktionen:

  php artisan project:git status
       Kurzstatus der Arbeitskopie.

  php artisan project:git log
       Letzten 20 Commits (oneline).

  php artisan project:git branch
       Lokale + Remote-Branches.

  php artisan project:git commit "Nachricht"
       git add . + git commit -m "Nachricht".

  php artisan project:git tag vX.Y.Z
       Annotierter Tag.

  php artisan project:git push
       Push + Tags.

  php artisan project:git pull
       Pull von origin.

Alle Aktionen erzeugen JSON-Audits unter:
  .audits/git/<YYYY-MM-DD>/audit-<timestamp>.json
TXT;
    }

    protected function buildCommand($action, $arg)
    {
        return match ($action) {
            'status' => 'git status --porcelain',
            'log'    => 'git log --decorate --oneline -20',
            'branch' => 'git branch -a',
            'commit' => $arg ? 'git add . && git commit -m "' . $arg . '"' : null,
            'tag'    => $arg ? 'git tag -a ' . $arg . ' -m "' . $arg . '"' : null,
            'push'   => 'git push --follow-tags',
            'pull'   => 'git pull',
            default  => null,
        };
    }

    protected function writeAudit($cmd, $success, $output, $error)
    {
        $date = now()->format('Y-m-d');
        $ts   = now()->format('Ymd-His');

        $dir  = base_path(".audits/git/$date");
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $audit = [
            'timestamp' => now()->toDateTimeString(),
            'project' => basename(base_path()),
            'cwd' => base_path(),
            'command' => $cmd,
            'success' => $success,
            'exit_code' => $success ? 0 : 1,
            'output' => $output,
            'errors' => $error,
        ];

        file_put_contents("$dir/audit-$ts.json", json_encode($audit, JSON_PRETTY_PRINT));
    }
}
