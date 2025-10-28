<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git-push {--tag=} {--message=} {--dry-run : Nur Simulation, keine echten Git-Befehle}';
    protected $description = 'Commit, push oder Tag für das aktuelle Projekt erstellen, mit Audit-Ausgabe.';

    public function handle(): int
    {
        $dryRun   = (bool)$this->option('dry-run');
        $tag      = $this->option('tag');
        $message  = $this->option('message') ?: ('auto push ' . now()->format('Y-m-d H:i:s'));
        $project  = basename(base_path());
        $userName = trim(shell_exec('whoami')) ?: 'unknown';
        $runId    = now()->format('Y-m-d H:i:s');

        // --- Pfade ---
        $auditDir = base_path('.logs/audits/git');
        File::ensureDirectoryExists($auditDir);
        $htmlFile = $auditDir . '/git.html';

        $this->info("[$runId] (php artisan project:git-push)");

        // --- Helper: exec wrapper ---
        $exec = function (string $cmd) use ($dryRun) {
            if ($dryRun) {
                return "[dry-run] $cmd";
            }
            $p = Process::fromShellCommandline($cmd);
            $p->setTimeout(300);
            $p->run();
            return trim($p->getOutput() . $p->getErrorOutput());
        };

        // --- Git status / add / commit / push ---
        $statusOut = $exec('git status -s');
        $exec('git add -A');
        $addedOut  = $exec('git diff --cached --name-only');
        $commitOut = $exec('git commit -m "' . addslashes($message) . '"');
        $pushOut   = $exec('git push');

        // --- Optional: Tag ---
        $tagOut = '';
        if ($tag) {
            $exec("git tag -a $tag -m \"" . addslashes($message ?: 'Checkpoint') . "\"");
            $tagOut = $exec("git push origin $tag");
        }

        // --- Audit vorbereiten ---
        $sections = [
            'status' => $statusOut,
            'add'    => $addedOut,
            'commit' => $commitOut,
            'push'   => $pushOut,
        ];
        if ($tag) {
            $sections['tag'] = $tagOut ?: '(kein Tag-Output)';
        }

        $htmlRun = [];
        $htmlRun[] = "<details>";
        $htmlRun[] = "<summary class=\"git-push\">Run-ID: {$runId}</summary>";

        $htmlRun[] = "<details><summary>Summary</summary>";
        $htmlRun[] = "<table class=\"value-table\">";
        $htmlRun[] = "<tr><td class=\"git-key\">Project</td><td class=\"git-value\">{$project}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">User</td><td class=\"git-value\">{$userName}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Message</td><td class=\"git-value\">" . htmlspecialchars($message) . "</td></tr>";
        if ($tag) {
            $htmlRun[] = "<tr><td class=\"git-key\">Tag</td><td class=\"git-value\">" . htmlspecialchars($tag) . "</td></tr>";
        }
        $htmlRun[] = "</table></details>";

        $htmlRun[] = "<details><summary>Files</summary><table class=\"git-table\">";
        foreach ($sections as $key => $text) {
            $display = nl2br(htmlspecialchars($this->formatNumbered($text)));
            $htmlRun[] = "<tr><td class=\"git-key\">$key</td><td class=\"git-value\">{$display}</td></tr>";
        }
        $htmlRun[] = "</table></details>";
        $htmlRun[] = "</details>";

        $newBlock = implode(PHP_EOL, $htmlRun) . PHP_EOL;

        // --- HTML schreiben: Einfügen vor dem Backlink-Block ---
        if (!$dryRun) {
            if (!File::exists($htmlFile)) {
                // initiales audit anlegen
                $header = <<<HTML
<!DOCTYPE html><html lang="de">
<head>
<meta charset="UTF-8">
<title>Git Audits — {$project}</title>
<link rel="stylesheet" href="../../audits-git.css">
</head>
<body>
<h1 class="git-header">Git Audit Log — {$project}</h1>
HTML;
                $footer = <<<HTML
<div class="backlink"><a href="../../audits-main.html">Back to Audits-Main</a></div>
</body></html>
HTML;
                File::put($htmlFile, $header . $newBlock . $footer);
            } else {
                $existing = File::get($htmlFile);

                $pos = strrpos($existing, '<div class="backlink">');
                if ($pos !== false) {
                    $updated = substr($existing, 0, $pos) . $newBlock . substr($existing, $pos);
                    File::put($htmlFile, $updated);
                } else {
                    File::append($htmlFile, $newBlock);
                }
            }
        }

        $this->info("Audit aktualisiert: $htmlFile");
        return Command::SUCCESS;
    }

    /**
     * Formatiert Git-Ausgabe nummeriert.
     */
    protected function formatNumbered(string $text): string
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($text));
        $lines = array_filter($lines, fn($l) => trim($l) !== '');
        if (empty($lines)) {
            return '(keine Dateien)';
        }
        $out = [];
        $i = 1;
        foreach ($lines as $ln) {
            $out[] = sprintf('%2d. %s', $i++, trim($ln));
        }
        return implode("\n", $out);
    }
}
