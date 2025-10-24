<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git-push {--dry-run : Simulate actions only}';
    protected $description = 'Push git changes with tafeld audit output (HTML + JSONL).';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $base = base_path();
        $envProj = env('PROJ_NAME');
        $project = $envProj ?: basename($base);
        $logDir = "$base/.logs/audits/git";
        $jsonLog = "$logDir/git.jsonl";
        $htmlLog = "$logDir/git.html";
        $cssPath = "$base/.logs/audits-git.css";

        File::ensureDirectoryExists($logDir);

        $runId = now()->format('Y-m-d H:i:s');
        $userName = trim(shell_exec('git config user.name')) ?: 'unknown';

        $this->info("Run-ID: $runId");
        $this->line("Dry run: " . ($dryRun ? 'yes' : 'no'));

        if (!is_dir("$base/.git")) {
            $this->error("No .git repository found in $base");
            return Command::FAILURE;
        }

        $exec = function (string $cmd) use ($dryRun) {
            $process = Process::fromShellCommandline($cmd, base_path());
            if ($dryRun) return "[dry-run] $cmd";
            $process->run();
            return trim($process->getOutput());
        };

        $result = [
            'timestamp' => now()->toIso8601String(),
            'run_id'    => $runId,
            'project'   => $project,
            'user'      => $userName,
            'result'    => 'ok',
            'output'    => [],
        ];

        $result['output']['status'] = $exec('git status -s');
        $result['output']['add'] = $exec('git add .');
        $result['output']['commit'] = $exec('git commit -m "auto push"');
        $result['output']['push'] = $exec('git push');

        if (!$dryRun) {
            File::append($jsonLog, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
        }

        $htmlRun = [];
        $htmlRun[] = "<details>";
        $htmlRun[] = "<summary class=\"git-push\">Run-ID: {$runId}</summary>";
        $htmlRun[] = "<details><summary>Summary</summary>";
        $htmlRun[] = "<table class=\"value-table\">";
        $htmlRun[] = "<tr><td class=\"git-key\">Project</td><td class=\"git-value\">{$project}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">User</td><td class=\"git-value\">{$userName}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Result</td><td class=\"git-value\">ok</td></tr>";
        $htmlRun[] = "</table></details>";
        $htmlRun[] = "<details><summary>Files</summary>";
        $htmlRun[] = "<table class=\"git-table\">";
        foreach ($result['output'] as $key => $value) {
            $display = htmlspecialchars($value);
            $htmlRun[] = "<tr><td class=\"git-key\">$key</td><td class=\"git-value\">{$display}</td></tr>";
        }
        $htmlRun[] = "</table></details>";
        $htmlRun[] = "</details>";

        if (!File::exists($htmlLog)) {
            $headerHtml = [];
            $headerHtml[] = "<!DOCTYPE html><html lang=\"de\">";
            $headerHtml[] = "<head>";
            $headerHtml[] = "    <meta charset=\"UTF-8\">";
            $headerHtml[] = "    <title>Git Audits — {$project}</title>";
            $headerHtml[] = "    <link rel=\"stylesheet\" href=\"../../audits-base.css\">";
            $headerHtml[] = "    <link rel=\"stylesheet\" href=\"../../audits-git.css\">";
            $headerHtml[] = "    <script type=\"module\" src=\"https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js\"></script>";
            $headerHtml[] = "    <script nomodule src=\"https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js\"></script>";
            $headerHtml[] = "</head><body>";
            $headerHtml[] = "<h1 class=\"git-header\">Git Audit Log — {$project}</h1>";
            $headerHtml[] = implode(PHP_EOL, $htmlRun);
            $headerHtml[] = "</body></html>";
            File::put($htmlLog, implode(PHP_EOL, $headerHtml) . PHP_EOL);
        } else {
            File::append($htmlLog, implode(PHP_EOL, $htmlRun) . PHP_EOL);
        }

        $this->info("Audit updated: $htmlLog");
        return Command::SUCCESS;
    }
}
