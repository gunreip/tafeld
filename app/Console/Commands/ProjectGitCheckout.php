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

        // ---------------------------------------------------------
        // 1. Audit-Setup (identisch zu ProjectGitPush)
        // ---------------------------------------------------------

        $project  = basename(base_path());
        $userName = trim(shell_exec('whoami')) ?: 'unknown';
        $laravel  = app()->version();
        $php      = PHP_VERSION;
        $runId    = now()->format('Y-m-d H:i:s');
        $context  = 'git';

        // Monats-Rotation und Verzeichnisstruktur
        $yearDir   = base_path(".logs/audits/{$context}/" . now()->format('Y'));
        $monthFile = now()->format('Y-m');
        $htmlFile  = "{$yearDir}/{$monthFile}-{$context}.html";
        $jsonFile  = "{$yearDir}/{$monthFile}-{$context}.jsonl";

        File::ensureDirectoryExists($yearDir);

        // Templates laden
        $headerTemplate = base_path('.logs/template-header.html');
        $footerTemplate = base_path('.logs/template-footer.html');
        $descFile       = base_path(".logs/audits/{$context}/audit-main-desc.txt");

        // Beschreibung (subtitle) lesen
        $subtitleContent = File::exists($descFile) ? File::get($descFile) : '(no description)';

        $header = File::exists($headerTemplate) ? File::get($headerTemplate) : '';
        $footer = File::exists($footerTemplate) ? File::get($footerTemplate) : '';

        // Datei neu erstellen, wenn sie noch nicht existiert
        if (!File::exists($htmlFile)) {
            // dynamische CSS-Pfade
            $baseCss = '../../../audits-base.css';
            $ctxCss  = "../../../audits-{$context}.css";
            $header = str_replace(
                ['../../audits-base.css', '../../audits-git.css', '../../../../audits-base.css', '../../../../audits-git.css'],
                [$baseCss, $ctxCss, $baseCss, $ctxCss],
                $header
            );

            // Backlink-Pfade anpassen
            $header = str_replace('../../audits-main.html', '../../../audits-main.html', $header);
            $footer = str_replace('../../audits-main.html', '../../../audits-main.html', $footer);

            // oberen Backlink nach Subtitle einfügen
            $insertPos = strpos($header, '</span>');
            if ($insertPos !== false) {
                $header = substr_replace(
                    $header,
                    "\n<div class=\"backlink\"><a href=\"../../../audits-main.html\">Back to Audits-Main</a></div>\n",
                    $insertPos + 7,
                    0
                );
            }

            // Platzhalter ersetzen
            $header = str_replace(
                ['{{project}}', '{{laravel_version}}', '{{php_version}}', '{{generated_at}}', '{{subtitle}}'],
                [$project, $laravel, $php, now()->format('Y-m-d H:i:s'), $subtitleContent],
                $header
            );
            $footer = str_replace(
                ['{{project}}', '{{laravel_version}}', '{{php_version}}', '{{generated_at}}'],
                [$project, $laravel, $php, now()->format('Y-m-d H:i:s')],
                $footer
            );

            // neue Audit-Dateien schreiben
            File::put($htmlFile, $header . $footer);
            File::put($jsonFile, '');

            $this->info("Neue Monats-Auditdateien erstellt: {$htmlFile}, {$jsonFile}");
        }

        // ---------------------------------------------------------
        // ab hier folgt deine bestehende Git-Checkout-Logik
        // ---------------------------------------------------------

        $base     = base_path();
        $envProj  = env('PROJ_NAME');
        $project  = $envProj ?: basename($base);
        $logDir   = "$base/.logs/audits/git";
        // $jsonFile  = "$logDir/git.jsonl";
        // $htmlFile  = "$logDir/git.html";
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
            if (preg_match('/\s/', $tag)) {
                $this->warn("Warning: Tag name contains spaces. Use '-' or '_' instead.");
                return Command::FAILURE;
            }
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

        // --- JSONL log ---
        if (!$dryRun) {
            $result = [
                'timestamp' => now()->toIso8601String(),
                'run_id'    => $runId,
                'project'   => $project,
                'user'      => $userName,
                'action'    => $action,
                'result'    => 'ok',
                'output'    => $output,
            ];
            File::append($jsonFile, json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
        }

        // --- HTML run block ---
        $htmlRun = [];
        $htmlRun[] = "<details>";
        $htmlRun[] = "<summary class=\"git-checkout\"><span class='run-id'>Run-ID: {$runId}</span><span class='run-id-context'>(Checkout)</span></summary>";
        $htmlRun[] = "<details><summary>Summary</summary>";
        $htmlRun[] = "<table class=\"value-table\">";
        $htmlRun[] = "<tr><td class=\"git-key\">Project</td><td class=\"git-value\">{$project}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">User</td><td class=\"git-value\">{$userName}</td></tr>";
        $htmlRun[] = "<tr><td class=\"git-key\">Action</td><td class=\"git-value\">{$action}</td></tr>";
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

        // --- HTML write/append ---
        if (!$dryRun) {
            if (!File::exists($htmlFile)) {
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
                $headerHtml[] = "<div class=\"subtitle\">{$desc}</div>";
                $headerHtml[] = "<div class=\"backlink\"><a href=\"../../audits-main.html\">Back to Audits-Main</a></div>";
                $headerHtml[] = implode(PHP_EOL, $htmlRun);
                $headerHtml[] = "<div class=\"backlink\"><a href=\"../../audits-main.html\">Back to Audits-Main</a></div>";
                $headerHtml[] = "<footer class=\"git-footer\">";
                $headerHtml[] = "Generated: " . now()->format('Y-m-d H:i:s') . " | Laravel {$laravel} | PHP {$php}";
                $headerHtml[] = "</footer></body></html>";
                File::put($htmlFile, implode(PHP_EOL, $headerHtml) . PHP_EOL);
            } else {
                $html = File::get($htmlFile);

                // --- find insertion point BEFORE last backlink above footer ---
                $footerPos   = strrpos($html, '<footer');
                $backlinkPos = strrpos(substr($html, 0, $footerPos), '<div class="backlink">');
                $insertPos   = $backlinkPos !== false ? $backlinkPos : $footerPos;

                if ($insertPos !== false) {
                    $newHtml = substr($html, 0, $insertPos)
                        . implode(PHP_EOL, $htmlRun) . PHP_EOL
                        . substr($html, $insertPos);
                    File::put($htmlFile, $newHtml);
                } else {
                    File::append($htmlFile, implode(PHP_EOL, $htmlRun) . PHP_EOL);
                }
            }
        }

        $this->info("Audit updated: $htmlFile");
        return Command::SUCCESS;
    }
}
