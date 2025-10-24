<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ProjectGitCheckout extends Command
{
    protected $signature = 'project:git-checkout
                            {--status : Show repo status only}
                            {--branch= : Switch or create branch}
                            {--tag= : Create or switch to tag}
                            {--restore= : Restore to existing tag}
                            {--message= : Commit or tag message}
                            {--dry-run : Simulate actions only}';

    protected $description = 'Perform or simulate Git branch/tag operations with tafeld audit output (HTML + JSONL).';

    public function handle(): int
    {
        $dryRun     = $this->option('dry-run');
        $onlyStatus = $this->option('status');
        $branch     = $this->option('branch');
        $tag        = $this->option('tag');
        $restore    = $this->option('restore');
        $message    = $this->option('message');

        $base     = base_path();
        $envProj  = env('PROJ_NAME');
        $project  = $envProj ?: basename($base);
        $logDir   = "$base/.logs/audits/git";
        $jsonLog  = "$logDir/git.jsonl";
        $htmlLog  = "$logDir/git.html";
        $descFile = "$logDir/audit-main-desc.txt";
        $cssPath  = "$base/.logs/audits-git.css";

        File::ensureDirectoryExists($logDir);

        $runId     = now()->format('Y-m-d H:i:s');
        $userName  = trim(shell_exec('git config user.name')) ?: 'unknown';
        $laravel   = app()->version();
        $php       = PHP_VERSION;

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
summary.git-checkout{background:#2b3a56;color:#9cf;}
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
                File::ensureDirectoryExists(dirname($cssPath));
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

        // --- determine action ---
        $action = null;
        $output = [];
        if ($onlyStatus) {
            $action = 'status';
            $output['branches']        = explode("\n", $exec('git branch --format="%(refname:short)"'));
            $output['tags']            = explode("\n", $exec('git tag --sort=-creatordate'));
            $output['head']            = $exec('git rev-parse --abbrev-ref HEAD');
            $output['latest_commits']  = explode("\n", $exec('git log -5 --oneline'));
        } elseif ($restore) {
            $action = 'restore';
            $output['restore'] = $exec("git checkout $restore");
        } elseif ($tag) {
            $action = 'create_tag';
            $exec("git add .");
            $exec("git commit -m \"" . addslashes($message ?: "Tagged $tag") . "\"");
            $output['tag']  = $exec("git tag -a $tag -m \"" . addslashes($message ?: "Checkpoint") . "\"");
            $output['push'] = $exec("git push origin $tag");
        } elseif ($branch) {
            $action = 'switch_branch';
            $existingBranches = explode("\n", $exec('git branch --format="%(refname:short)"'));
            if (in_array($branch, $existingBranches)) {
                $output['checkout'] = $exec("git checkout $branch");
            } else {
                $output['new_branch'] = $exec("git checkout -b $branch");
                $output['push']       = $exec("git push -u origin $branch");
            }
        } else {
            $this->warn("No action specified. Use --status, --branch, --tag, or --restore.");
            return Command::SUCCESS;
        }

        // --- Build meta & files (ProjectGitPush-compatible) ---
        $meta = [
            'run_id'        => now()->format('Ymd-His'),
            'run_id_human'  => $runId,
            'project'       => $project,
            'date'          => now()->format('Y-m-d'),
            'time'          => now()->format('H:i:s'),
            '@source'       => 'project:git-checkout',
            '@target'       => 'git.jsonl',
            'action'        => $action,
            'branch'        => $branch ?? '',
            'tag'           => $tag ?? '',
            'user'          => $userName,
            'php'           => $php,
            'laravel'       => $laravel,
            'result'        => 'ok',
            'files_count'   => count($output),
            'comment'       => $message ?: '',
            'summary'       => "Git {$action} executed for project {$project}"
        ];

        $files = [];
        foreach ($output as $key => $val) {
            $files[] = [
                'file'     => $key,
                'action'   => $action,
                'info'     => is_array($val) ? implode("\n", $val) : $val,
                'cmd'      => '',
                'exitcode' => 0,
                'duration' => '',
                'stage'    => '',
                'mode'     => $dryRun ? 'dry-run' : 'exec',
            ];
        }

        // --- JSONL log ---
        if (!$dryRun) {
            File::append($jsonLog, json_encode(['meta' => $meta, 'files' => $files], JSON_UNESCAPED_SLASHES) . "\n");
        }

        // --- HTML audit (unchanged logic) ---
        $htmlRun = [];
        $htmlRun[] = "<details>";
        $htmlRun[] = "<summary class=\"git-checkout\">Run-ID: {$runId}</summary>";
        $htmlRun[] = "<details><summary>Summary</summary>";
        $htmlRun[] = "<table class=\"value-table\">";
        $htmlRun[] = "<tr><td class=\"git-key\">Project</td><td class=\"git-value\">{$project}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">User</td><td class=\"git-value\">{$userName}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Action</td><td class=\"git-value\">{$action}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Branch</td><td class=\"git-value\">" . ($branch ?? '-') . "</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Tag</td><td class=\"git-value\">" . ($tag ?? '-') . "</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Result</td><td class=\"git-value\">ok</td></tr>";
        $htmlRun[] = "</table></details>";
        $htmlRun[] = "<details><summary>Files</summary>";
        $htmlRun[] = "<table class=\"git-table\">";
        foreach ($output as $key => $value) {
            $display = is_array($value)
                ? implode('<br>', array_map('htmlspecialchars', $value))
                : htmlspecialchars($value);
            $htmlRun[] = "<tr><td class=\"git-key\">$key</td><td class=\"git-value\">{$display}</td></tr>";
        }
        $htmlRun[] = "</table></details>";
        $htmlRun[] = "</details>";

        if (!$dryRun) {
            if (!File::exists($htmlLog)) {
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
