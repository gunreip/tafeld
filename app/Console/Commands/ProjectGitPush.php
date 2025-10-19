<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git {--message=Automated commit} {--branch=main} {--remote=origin}';
    protected $description = 'Commit und Push des Projekts mit farbcodiertem HTML/JSONL-Git-Audit.';

    public function handle(): int
    {
        $project = env('PROJ_NAME', basename(base_path()));
        $repoUrl = "https://github.com/gunreip/{$project}.git";
        $base = base_path();
        $branch = $this->option('branch');
        $remote = $this->option('remote');
        $message = $this->option('message');

        $this->ensureGitInitialized($base, $branch, $remote, $repoUrl);
        $this->ensureGitignore($base);

        $status = $this->shell('git status --porcelain -z');
        $files = $this->parseGitStatus($status);
        $summaryBefore = $this->shell('git diff --shortstat');

        $this->shellPassthru('git add -A');
        $this->shell('git commit -m ' . escapeshellarg($message));
        $this->shellPassthru("git push " . escapeshellarg($remote) . ' ' . escapeshellarg($branch));

        $summaryAfter = $this->shell('git show --stat --oneline -1');
        $meta = $this->buildMeta($project, $base, "{$repoUrl} [{$branch}]", $message, $files, $summaryBefore, $summaryAfter);
        $this->appendAudit($meta, $files);
        $this->appendChangelog($meta);

        $this->info('✓ Git-Push und Audit abgeschlossen.');
        return Command::SUCCESS;
    }

    protected function ensureGitInitialized(string $base, string $branch, string $remote, string $repoUrl): void
    {
        if (!File::exists($base.'/.git')) {
            $this->shellPassthru('git init');
            $this->shellPassthru("git branch -M " . escapeshellarg($branch));
            $this->shellPassthru("git remote add " . escapeshellarg($remote) . ' ' . escapeshellarg($repoUrl));
        } else {
            $remotes = trim($this->shell('git remote'));
            if (strpos($remotes, $remote) === false) {
                $this->shellPassthru("git remote add " . escapeshellarg($remote) . ' ' . escapeshellarg($repoUrl));
            }
        }
    }

    protected function ensureGitignore(string $base): void
    {
        $path = $base . '/.gitignore';
        if (!File::exists($path)) {
            $content = "/vendor/\n/node_modules/\n/.env\n/.idea/\n/.vscode/\n/storage/*.key\n/storage/*.log\n/.logs/\n/logs/\n*.bak\n*.zip\n*.tar.gz\n";
            File::put($path, $content);
        }
    }

    protected function parseGitStatus(string $porcelainZ): array
    {
        $entries = [];
        $parts = array_filter(explode("\0", $porcelainZ));
        foreach ($parts as $line) {
            $code = substr($line, 0, 2);
            $path = trim(substr($line, 3));
            $action = $this->mapAction($code);
            if ($action) $entries[] = ['file' => $path, 'action' => $action];
        }
        return $entries;
    }

    protected function mapAction(string $code): ?string
    {
        return match(true) {
            $code === '??' => 'new',
            str_contains($code, 'D') => 'delete',
            str_contains($code, 'R') => 'rename',
            str_contains($code, 'A') => 'add',
            str_contains($code, 'M') => 'modify',
            str_contains($code, 'U') => 'update',
            default => null,
        };
    }

    protected function buildMeta(string $project, string $source, string $target, string $comment, array $files, string $sumBefore, string $sumAfter): array
    {
        $runAt = now();
        $runId = $runAt->format('Ymd-His');
        $summary = trim($sumAfter) ?: $sumBefore;
        return [
            'run_id' => $runId,
            'run_id_human' => $runAt->format('Y-m-d H:i:s'),
            'project' => $project,
            'date' => $runAt->format('Y-m-d'),
            'time' => $runAt->format('H:i:s'),
            '@source' => $source,
            '@target' => $target,
            'files_count' => count($files),
            'comment' => $comment,
            'summary' => $summary,
        ];
    }

    protected function appendAudit(array $meta, array $files): void
    {
        $dir = base_path('.logs/audits/git');
        File::ensureDirectoryExists($dir);
        $css = base_path('.logs/audits-git.css');
        if (!File::exists($css)) File::put($css, $this->defaultCss());

        $html = $dir . '/git.html';
        $json = $dir . '/git.jsonl';
        File::append($json, json_encode(['meta'=>$meta,'files'=>$files], JSON_UNESCAPED_SLASHES)."\n");

        if (!File::exists($html)) {
            $head = '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Git Audits</title>'.
                    '<link rel="stylesheet" href="../../audits-git.css">'.
                    '<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>'.
                    '<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>'.
                    '</head><body><h1>Git Audits</h1>';
            File::put($html,$head);
        }
        File::append($html, $this->renderRunHtml($meta,$files)."\n");
    }

    protected function renderRunHtml(array $m,array $files):string
    {
        $id = $m['run_id'];
        $header = 'Run-ID: '.$m['run_id_human'];
        $metaTable = "<tr><th>Date</th><td class='value-date'>{$m['date']}</td></tr>".
                     "<tr><th>Time</th><td class='value-time'>{$m['time']}</td></tr>".
                     "<tr><th>Project</th><td class='value-project'>{$m['project']}</td></tr>".
                     "<tr><th>@source</th><td class='value-source'>{$m['@source']}</td></tr>".
                     "<tr><th>@target</th><td class='value-target'>{$m['@target']}</td></tr>".
                     "<tr><th>Files</th><td class='value-counts'>{$m['files_count']}</td></tr>".
                     "<tr><th>Comment</th><td class='value-comment'>{$m['comment']}</td></tr>";
        $summary = "<tr><th>Summary</th><td class='git-okay'>".nl2br(htmlspecialchars($m['summary'],ENT_QUOTES))."</td></tr>";
        $rows='';
        foreach($files as $f){
            $cls='git-'.$f['action'];$icon=$this->actionIcon($f['action']);
            $rows.="<tr class='{$cls}'><td>{$icon} ".htmlspecialchars($f['file'],ENT_QUOTES)."</td></tr>";
        }
        return "<details id='run-{$id}'><summary>{$header}</summary>".
               "<table class='meta'>{$metaTable}</table>".
               "<details class='summary'><summary>Summary</summary><table class='summary-table'>{$summary}</table></details>".
               "<details class='files'><summary>Files</summary><table class='files'>{$rows}</table></details></details>";
    }

    protected function actionIcon(string $a):string
    {
        $m=['add'=>'cloud-upload-outline','new'=>'cloud-upload-outline','modify'=>'sync-outline','update'=>'sync-outline','rename'=>'shuffle-outline','delete'=>'trash-outline'];
        $n=$m[$a]??'information-circle-outline';
        return "<ion-icon name='{$n}'></ion-icon>";
    }

    protected function defaultCss():string
    {
        return ':root{--bg:#0f1115;--fg:#e6e6e6;--border:#2a2f3a;--accent:#7cc;}body{background:var(--bg);color:var(--fg);font:14px monospace;padding:16px;}h1{color:var(--accent);}details{border:1px solid var(--border);border-radius:8px;margin:12px 0;padding:8px 12px;background:#131722;}summary{cursor:pointer;color:var(--accent);}summary:hover{color:#fff;text-shadow:0 0 4px #6cf;}th,td{border:1px solid var(--border);padding:6px 8px;}th{background:#171b26;text-align:left;width:180px;}ion-icon{font-size:16px;vertical-align:middle;margin-right:6px;transition:filter .2s;}ion-icon:hover{filter:drop-shadow(0 0 4px currentColor);} .git-add ion-icon,.git-new ion-icon{color:#5f5;} .git-delete ion-icon{color:#f66;} .git-modify ion-icon,.git-update ion-icon{color:#ff9;} .git-rename ion-icon{color:#9cf;} .git-okay{color:#7f7;} .value-date{color:#9fc;} .value-time{color:#9cf;} .value-comment{color:#fc9;}';
    }

    protected function appendChangelog(array $meta):void
    {
        $f=base_path('docs/changelog.md');if(!File::exists($f))return;
        File::append($f,"- Git Push [{$meta['run_id_human']}](.logs/audits/git/git.html#run-{$meta['run_id']}) → {$meta['@target']} : {$meta['comment']}\n");
    }

    protected function shell(string $c):string{return trim((string)@shell_exec($c.' 2>&1'));}
    protected function shellPassthru(string $c):void{$this->line('$ '.$c);passthru($c);}
}
