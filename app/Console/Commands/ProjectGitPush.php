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
        $dryRun   = $this->option('dry-run');
        $base     = base_path();
        $envProj  = env('PROJ_NAME');
        $project  = $envProj ?: basename($base);
        $logDir   = "$base/.logs/audits/git";
        $jsonLog  = "$logDir/git.jsonl";
        $htmlLog  = "$logDir/git.html";
        $descFile = "$logDir/audit-main-desc.txt";
        $cssPath  = "$base/.logs/audits-git.css";

        File::ensureDirectoryExists($logDir);

        $runId    = now()->format('Y-m-d H:i:s');
        $userName = trim(shell_exec('git config user.name')) ?: 'unknown';
        $laravel  = app()->version();
        $php      = PHP_VERSION;

        $this->info("Run-ID: $runId");
        $this->line("Dry run: " . ($dryRun ? 'yes' : 'no'));

        if (!is_dir("$base/.git")) {
            $this->error("No .git repository found in $base");
            return Command::FAILURE;
        }

        // --- CSS existence check ---
        if (!File::exists($cssPath)) {
            $defaultCss = <<<CSS
/* audits-git.css — auto-created */
body,table{font-family:monospace;font-size:13px;color:#ddd;background:#222;}
details{margin:6px 0;padding:2px 0;}
summary{cursor:pointer;color:#fff;background:#333;padding:4px 6px;border-radius:4px;}
summary.git-push{background:#2b563a;color:#9f9;}
table{width:100%;border-collapse:collapse;margin:6px 0;}
td.git-key{width:160px;color:#aaa;padding:2px 4px;vertical-align:top;}
td.git-value{color:#fff;padding:2px 4px;word-break:break-all;}
.value-table td{border-bottom:1px solid #444;}
.git-table td{border-bottom:1px dashed #444;}
ion-icon{font-size:16px;margin-right:4px;vertical-align:middle;opacity:0.8;}
details:hover summary{background:#444;}
.git-header{background:#1a1a1a;padding:8px;color:#9cf;border-bottom:1px solid #333;}
.git-footer{font-size:12px;color:#999;text-align:center;margin-top:12px;padding:6px 0;border-top:1px solid #333;}
.backlink{text-align:center;margin:10px 0;}
.backlink a{color:#9cf;text-decoration:none;}
.backlink a:hover{text-decoration:underline;}
CSS;
            if (!$dryRun) {
                File::put($cssPath, $defaultCss);
            }
            $this->info("Created missing CSS: $cssPath");
        }

        // --- helper exec ---
        $exec = function (string $cmd) use ($dryRun) {
            $process = Process::fromShellCommandline($cmd, base_path());
            if ($dryRun) return "[dry-run] $cmd";
            $process->run();
            return trim($process->getOutput());
        };

        // --- Git operations ---
        $result = [
            'timestamp' => now()->toIso8601String(),
            'run_id'    => $runId,
            'project'   => $project,
            'user'      => $userName,
            'result'    => 'ok',
            'output'    => [],
        ];

        $result['output']['status'] = $exec('git status -s');
        $result['output']['add']    = $exec('git add .');
        $result['output']['commit'] = $exec('git commit -m "auto push"');
        $result['output']['push']   = $exec('git push');

        if (!$dryRun) {
            File::append($jsonLog, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
        }

        // --- HTML run block ---
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

        // --- HTML write/append ---
        if (!$dryRun) {
            if (!File::exists($htmlLog)) {
                // Subheader content
                $desc = File::exists($descFile)
                    ? File::get($descFile)
                    : 'No description available.';
                $desc = preg_replace_callback('/<[^>]+>/', function ($m) {
                    return stripos($m[0], '<span') === 0 || stripos($m[0], '</span') === 0
                        ? $m[0]
                        : htmlspecialchars($m[0]);
                }, $desc);

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
                $headerHtml[] = "<div class=\"git-subheader\">{$desc}</div>";
                $headerHtml[] = "<a class=\"backlink\" href=\"../../audits-main.html\">Back to Audits-Main</a>";
                $headerHtml[] = implode(PHP_EOL, $htmlRun);
                $headerHtml[] = "<a class=\"backlink\" href=\"../../audits-main.html\">Back to Audits-Main</a>";
                $headerHtml[] = "<footer class=\"git-footer\">";
                $headerHtml[] = "Generated: " . now()->format('Y-m-d H:i:s') . " | Laravel {$laravel} | PHP {$php}";
                $headerHtml[] = "</footer></body></html>";
                File::put($htmlLog, implode(PHP_EOL, $headerHtml) . PHP_EOL);
            } else {
                $html = File::get($htmlLog);
                // Insert before last backlink/footer
                $insertPos = strrpos($html, '<div class="backlink">');
                if ($insertPos === false) {
                    $insertPos = strrpos($html, '</footer>');
                }
                if ($insertPos !== false) {
                    $newHtml = substr($html, 0, $insertPos)
                        . implode(PHP_EOL, $htmlRun) . PHP_EOL
                        . substr($html, $insertPos);
                    File::put($htmlLog, $newHtml);
                } else {
                    File::append($htmlLog, implode(PHP_EOL, $htmlRun) . PHP_EOL);
                }
            }
        }

        $this->info("Audit updated: $htmlLog");
        return Command::SUCCESS;
    }
}
