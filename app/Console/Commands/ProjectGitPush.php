<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git-push {--tag=}';
    protected $description = 'Commit, push oder Tag für das aktuelle Projekt erstellen.';

    public function handle()
    {
        $project = basename(base_path());
        $user = trim(shell_exec('whoami'));
        $tag = $this->option('tag');
        $timestamp = now()->format('Y-m-d H:i:s');
        $output = [
            'summary' => [
                'project' => $project,
                'user'    => $user,
                'result'  => 'ok',
            ],
            'files' => [],
        ];

        $exec = function ($cmd) {
            return trim(shell_exec($cmd . ' 2>&1'));
        };

        $this->info("[$timestamp] (php artisan project:git-push)");

        // Git status
        $status = $exec('git status -s');
        $output['files']['status'] = $this->formatFileList(explode("\n", $status));

        // Git add
        $exec('git add -A');
        $added = $exec('git diff --cached --name-only');
        $output['files']['add'] = $this->formatFileList(explode("\n", $added));

        // Git commit
        $commitMessage = "auto push " . now()->format('Y-m-d H:i:s');
        $commit = $exec("git commit -m \"$commitMessage\"");
        $output['files']['commit'] = $this->formatFileList(explode("\n", $commit));

        // Git push
        $push = $exec('git push');
        $output['files']['push'] = $this->formatFileList(explode("\n", $push));

        // Optional Tagging
        if ($tag) {
            $exec("git tag -a $tag -m \"Checkpoint\"");
            $exec("git push origin $tag");
        }

        // Audit-HTML schreiben
        $auditDir = base_path('logs/audits/git');
        File::ensureDirectoryExists($auditDir);
        $html = $this->buildHtml($timestamp, $output);
        File::put("$auditDir/git.html", $html);

        $this->info("Git push abgeschlossen und Audit aktualisiert.");
    }

    /**
     * Formatiert Dateilisten nummeriert und übersichtlich.
     */
    protected function formatFileList(array $lines): string
    {
        $lines = array_filter(array_map('trim', $lines));
        if (empty($lines)) {
            return '(keine Dateien)';
        }
        $out = [];
        $i = 1;
        foreach ($lines as $line) {
            if ($line === '') continue;
            $out[] = sprintf('%2d. %s', $i++, $line);
        }
        return implode("\n", $out);
    }

    /**
     * Baut eine einfache HTML-Audit-Ausgabe.
     */
    protected function buildHtml(string $timestamp, array $output): string
    {
        $html = [];
        $html[] = "<details open><summary>Run-ID: {$timestamp}</summary>";
        $html[] = "<h4>(php artisan project:git-push)</h4>";

        $html[] = "<details><summary>Summary</summary><pre>" .
            htmlspecialchars(json_encode($output['summary'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) .
            "</pre></details>";

        $html[] = "<details><summary>Files</summary>";

        foreach (['status', 'add', 'commit', 'push'] as $section) {
            $html[] = "<details><summary>{$section}</summary><pre>" .
                htmlspecialchars($output['files'][$section] ?? '(keine Daten)') .
                "</pre></details>";
        }

        $html[] = "</details></details>";

        return implode("\n", $html);
    }
}
