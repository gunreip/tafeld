<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git 
        {--message=Automated commit} 
        {--branch=main} 
        {--remote=origin}';
        
    protected $description = 'Initialisiert bei Bedarf, committet und pusht das Projekt. Erstellt HTML/JSONL-Audit in .logs/audits/git/ und optional .gitignore.';

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

        $this->line('→ add -A');
        $this->shellPassthru('git add -A');
        $this->line('→ commit');
        $commitOut = $this->shell('git commit -m ' . escapeshellarg($message));
        if (strpos($commitOut, 'nothing to commit') !== false) {
            $this->info('Keine Änderungen zu committen. Erzeuge trotzdem Audit-Eintrag.');
        }

        $this->line("→ push {$remote} {$branch}");
        $pushOut = $this->shell('git push ' . escapeshellarg($remote) . ' ' . escapeshellarg($branch));

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
            $this->info('→ Initialisiere Git-Repository');
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
        $ignorePath = $base . '/.gitignore';
        if (!File::exists($ignorePath)) {
            $this->info('→ Erstelle .gitignore');
            $content = "/vendor/\n/node_modules/\n/.env\n/.idea/\n/.vscode/\n/storage/*.key\n/storage/*.log\n/.logs/\n/logs/\n*.bak\n*.zip\n*.tar.gz\n";
            File::put($ignorePath, $content);
        }
    }

    protected function parseGitStatus(string $porcelainZ): array
    {
        $entries = [];
        $parts = array_values(array_filter(explode("\0", $porcelainZ), fn($x) => $x !== ''));
        foreach ($parts as $line) {
            $code = substr($line, 0, 2);
            $path = trim(substr($line, 3));
            $action = $this->mapAction($code);
            if ($action) {
                $entries[] = ['file' => $path, 'action' => $action];
            }
        }
        return $entries;
    }

    protected function mapAction(string $code): ?string
    {
        $code = trim($code);
        if ($code === '??') return 'new';
        if (str_contains($code, 'D')) return 'delete';
        if (str_contains($code, 'R')) return 'rename';
        if (str_contains($code, 'A')) return 'add';
        if (str_contains($code, 'M')) return 'modify';
        if (str_contains($code, 'U')) return 'update';
        return null;
    }

    protected function buildMeta(string $project, string $source, string $target, string $comment, array $files, string $sumBefore, string $sumAfter): array
    {
        $runAt = now();
        $runIdHuman = $runAt->format('Y-m-d H:i:s');
        $runId = $runAt->format('Ymd-His');

        $dirs = [];
        foreach ($files as $f) {
            $dirs[] = dirname($f['file']);
        }
        $dirs = array_values(array_unique(array_filter($dirs, fn($d) => $d !== '.' )));

        $summary = trim($sumAfter) !== '' ? $sumAfter : $sumBefore;

        return [
            'run_id' => $runId,
            'run_id_human' => $runIdHuman,
            'date' => $runAt->format('Y-m-d'),
            'time' => $runAt->format('H:i:s'),
            'project' => $project,
            '@source' => $source,
            '@target' => $target,
            'dirs_count' => count($dirs),
            'files_count' => count($files),
            'comment' => $comment,
            'summary' => trim($summary),
        ];
    }

    protected function appendAudit(array $meta, array $files): void
    {
        $auditDir = base_path('.logs/audits/git');
        File::ensureDirectoryExists($auditDir);

        $cssPath = base_path('.logs/audits-git.css');
        if (!File::exists($cssPath)) {
            File::put($cssPath, $this->defaultCss());
        }

        $htmlPath = $auditDir . '/git.html';
        $jsonlPath = $auditDir . '/git.jsonl';

        $jsonRecord = json_encode(['meta' => $meta, 'files' => $files], JSON_UNESCAPED_SLASHES);
        File::append($jsonlPath, $jsonRecord . "\n");

        if (!File::exists($htmlPath)) {
            $head = '<!DOCTYPE html><html lang="de"><head><meta charset="UTF-8"><title>Git Audits</title>'
                  . '<link rel="stylesheet" href="../../audits-git.css">'
                  . '<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>'
                  . '<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>'
                  . '</head><body><h1>Git Audits</h1>';
            File::put($htmlPath, $head);
        }

        $htmlBlock = $this->renderRunHtml($meta, $files);
        File::append($htmlPath, $htmlBlock . "\n");
    }

    protected function renderRunHtml(array $m, array $files): string
    {
        $id = htmlspecialchars($m['run_id'], ENT_QUOTES);
        $header = htmlspecialchars('Run-ID: ' . $m['run_id_human'], ENT_QUOTES);

        $metaRows = '';
        $pairs = [
            'Date' => $m['date'],
            'Time' => $m['time'],
            'Project' => $m['project'],
            '@source' => $m['@source'],
            '@target' => $m['@target'],
            'Anzahl Dirs/Files' => $m['dirs_count'] . ' / ' . $m['files_count'],
            'Comment' => $m['comment'],
            'Summary' => $m['summary'],
        ];
        foreach ($pairs as $k => $v) {
            $metaRows .= '<tr><th>'.htmlspecialchars($k, ENT_QUOTES).'</th><td>'.nl2br(htmlspecialchars((string)$v, ENT_QUOTES)).'</td></tr>';
        }

        $fileRows = '';
        foreach ($files as $f) {
            $icon = $this->actionIcon($f['action']);
            $fileRows .= '<tr><td><code>'.htmlspecialchars($f['file'], ENT_QUOTES).'</code></td><td class="icon-cell">'.$icon.'</td></tr>';
        }

        return '<details id="run-'.$id.'" open><summary>'.$header.'</summary>'
             . '<table class="meta">'.$metaRows.'</table>'
             . '<details class="files"><summary>Files</summary><table class="files"><thead><tr><th>File</th><th>Action</th></tr></thead><tbody>'
             . $fileRows
             . '</tbody></table></details></details>';
    }

    protected function actionIcon(string $action): string
    {
        $map = [
            'add' => 'cloud-upload-outline',
            'new' => 'cloud-upload-outline',
            'modify' => 'sync-outline',
            'update' => 'sync-outline',
            'rename' => 'shuffle-outline',
            'delete' => 'trash-outline',
        ];
        $name = $map[$action] ?? 'information-circle-outline';
        return '<ion-icon class="icon" name="'.$name.'"></ion-icon>';
    }

    protected function defaultCss(): string
    {
        return <<<CSS
:root { --bg:#0f1115; --fg:#e6e6e6; --muted:#9aa; --accent:#7cc; --border:#2a2f3a; }
body { background:var(--bg); color:var(--fg); font:14px/1.5 ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace; padding:16px; }
h1 { color:var(--accent); font-size:18px; margin:0 0 12px; }
details { border:1px solid var(--border); border-radius:8px; padding:8px 12px; margin:12px 0; background:#131722; }
summary { cursor:pointer; color:var(--accent); outline:none; }
table { width:100%; border-collapse:collapse; margin:8px 0; }
th, td { border:1px solid var(--border); padding:6px 8px; vertical-align:top; }
th { background:#171b26; text-align:left; width:220px; }
.files thead th { background:#151924; }
code { background:#0c0f16; padding:2px 4px; border-radius:4px; }
.icon { font-size:18px; vertical-align:middle; }
.icon-cell { width:120px; text-align:center; }
CSS;
    }

    protected function appendChangelog(array $meta): void
    {
        $file = base_path('docs/changelog.md');
        if (!File::exists($file)) return;
        $rel = '.logs/audits/git/git.html#run-' . $meta['run_id'];
        $line = "- Git Push [{$meta['run_id_human']}]({$rel}) → {$meta['@target']} : {$meta['comment']}\n";
        File::append($file, $line);
    }

    protected function shell(string $cmd): string
    {
        return trim((string)@shell_exec($cmd . ' 2>&1'));
    }

    protected function shellPassthru(string $cmd): void
    {
        $this->line('$ ' . $cmd);
        passthru($cmd);
    }
}
