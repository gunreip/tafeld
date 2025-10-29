<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class ProjectGitPush extends Command
{
    protected $signature = 'project:git-push 
        {--tag=} 
        {--message=} 
        {--dry-run : Nur Simulation, keine echten Git-Befehle}
        {--tag-list : Listet vorhandene Git-Tags und protokolliert sie im Audit}
        {--tag-delete= : Löscht einen oder mehrere Tags (kommagetrennt, lokal + remote)}';

    protected $description = 'Commit, Push oder Tag-Verwaltung für das aktuelle Projekt mit Monatsrotation und HTML/JSON-Audit-Output.';

    public function handle(): int
    {
        $dryRun   = (bool)$this->option('dry-run');
        $tag      = $this->option('tag');
        $message  = $this->option('message') ?: ('auto push ' . now('UTC')->format('Y-m-d H:i:s'));
        $project  = basename(base_path());
        $userName = trim(shell_exec('whoami')) ?: 'unknown';
        $runId    = now('UTC')->format('Y-m-d H:i:s');
        $laravel  = app()->version();
        $php      = PHP_VERSION;
        $context  = 'git';

        // -------------------------------------------
        // Monatsrotation und Dateistruktur prüfen
        // -------------------------------------------
        $yearDir   = base_path('.logs/audits/' . $context . '/' . now('UTC')->format('Y'));
        $monthFile = now('UTC')->format('Y-m');
        $htmlFile  = "{$yearDir}/{$monthFile}-{$context}.html";
        $jsonFile  = "{$yearDir}/{$monthFile}-{$context}.jsonl";

        File::ensureDirectoryExists($yearDir);

        // Templates und Beschreibung laden
        $headerTemplate = base_path('.logs/template-header.html');
        $footerTemplate = base_path('.logs/template-footer.html');
        $descFile       = base_path(".logs/audits/{$context}/audit-main-desc.txt");
        $subtitleContent = File::exists($descFile) ? File::get($descFile) : '(no description)';

        $header = File::exists($headerTemplate) ? File::get($headerTemplate) : '';
        $footer = File::exists($footerTemplate) ? File::get($footerTemplate) : '';

        // Auditdatei neu anlegen falls nötig
        if (!File::exists($htmlFile)) {
            $baseCss = '../../../audits-base.css';
            $ctxCss  = "../../../audits-{$context}.css";
            $header = str_replace(
                ['../../audits-base.css', '../../audits-git.css', '../../../../audits-base.css', '../../../../audits-git.css'],
                [$baseCss, $ctxCss, $baseCss, $ctxCss],
                $header
            );
            $header = str_replace('../../audits-main.html', '../../../audits-main.html', $header);
            $footer = str_replace('../../audits-main.html', '../../../audits-main.html', $footer);
            $insertPos = strpos($header, '</span>');
            if ($insertPos !== false) {
                $header = substr_replace(
                    $header,
                    "\n<div class=\"backlink\"><a href=\"../../../audits-main.html\">Back to Audits-Main</a></div>\n",
                    $insertPos + 7,
                    0
                );
            }
            $header = str_replace(
                ['{{project}}', '{{laravel_version}}', '{{php_version}}', '{{generated_at}}', '{{subtitle}}'],
                [$project, $laravel, $php, now('UTC')->format('Y-m-d H:i:s'), $subtitleContent],
                $header
            );
            $footer = str_replace(
                ['{{project}}', '{{laravel_version}}', '{{php_version}}', '{{generated_at}}'],
                [$project, $laravel, $php, now('UTC')->format('Y-m-d H:i:s')],
                $footer
            );
            if (!$dryRun) {
                File::put($htmlFile, $header . $footer);
                File::put($jsonFile, '');
            }
            $this->info("Neue Monats-Auditdateien erstellt: {$htmlFile}, {$jsonFile}");
        }

        // -------------------------------------------
        // 2a. Git-Tag-Liste (unverändert, falls vorhanden)
        // -------------------------------------------
        if ($this->option('tag-list')) {
            $this->info("[$runId] (php artisan project:git-push --tag-list)");
            $exec = fn($cmd) => $dryRun ? "[dry-run] $cmd" : trim(shell_exec($cmd . ' 2>&1'));
            $raw = $exec("git tag --list --sort=-creatordate --format='%(creatordate:iso) | %(tag) | %(subject) | %(taggername)'");
            $lines = array_values(array_filter(preg_split("/\r\n|\n|\r/", $raw), fn($l) => trim($l) !== ''));
            $rows = [];
            foreach ($lines as $i => $ln) {
                [$date, $t, $msg, $a] = array_map('trim', explode('|', $ln, 4)) + ['', '', '', ''];
                $rows[] = "<tr><td class='git-counter'>" . ($i + 1) . "</td>"
                    . "<td class='git-tags-date'>" . htmlspecialchars($date) . "</td>"
                    . "<td class='git-tags-tag'>" . htmlspecialchars($t) . "</td>"
                    . "<td class='git-tags-message'>" . htmlspecialchars($msg) . "</td>"
                    . "<td class='git-tags-author'>" . htmlspecialchars($a) . "</td></tr>";
            }
            $table = "<table class='git-tags-table'><thead><tr>"
                . "<th class='git-counter'>#</th>"
                . "<th class='git-tags-date'>Datum</th>"
                . "<th class='git-tags-tag'>Tag</th>"
                . "<th class='git-tags-message'>Message</th>"
                . "<th class='git-tags-author'>Autor</th>"
                . "</tr></thead><tbody>" . ($rows ? implode("\n", $rows) : "<tr><td colspan='5'>(keine Tags gefunden)</td></tr>") . "</tbody></table>";
            $htmlRun = "<details><summary class='git-push'>"
                . "<span class='run-id'>Run-ID: {$runId}</span>"
                . "<span class='run-id-context'>(Tag List)</span>"
                . "</summary><details><summary>Aktuelle Tags"
                . "</summary>{$table}</details></details>";
            $existing = File::get($htmlFile);
            $pos = strrpos($existing, '<div class="backlink">');
            $updated = ($pos !== false) ? substr($existing, 0, $pos) . $htmlRun . substr($existing, $pos) : $existing . $htmlRun;
            File::put($htmlFile, $updated);
            $this->info("Tag-Liste (Tabelle) ins Audit eingetragen: {$htmlFile}");
            return Command::SUCCESS;
        }

        // -------------------------------------------
        // 2b. Git-Tag-Delete (erweitert mit Datum + Author)
        // -------------------------------------------
        if ($this->option('tag-delete')) {
            $tags = array_filter(array_map('trim', explode(',', $this->option('tag-delete'))));
            if (empty($tags)) {
                $this->error('Keine Tags angegeben.');
                return Command::FAILURE;
            }

            $this->info("[$runId] (php artisan project:git-push --tag-delete)");

            $rowsHtml = [];
            $jsonTags = [];

            // Git-Author aus Konfiguration oder Systembenutzer
            $gitAuthor = trim(shell_exec('git config user.name')) ?: $userName;

            foreach ($tags as $i => $t) {
                // $currentTime = now('UTC')->format('Y-m-d H:i:s');
                $currentTime = now('UTC')->setTimezone(date_default_timezone_get())->format('Y-m-d H:i:s');

                if ($dryRun) {
                    $local = '<span class="dryrun">dry-run</span>';
                    $remote = '<span class="dryrun">dry-run</span>';
                    $status = '<span class="preview">vorschau</span>';
                } else {
                    $localOut  = trim(shell_exec("git tag -d " . escapeshellarg($t) . " 2>&1"));
                    $remoteOut = trim(shell_exec("git push origin :refs/tags/" . escapeshellarg($t) . " 2>&1"));

                    $localOk  = str_contains($localOut, "Deleted tag") ? 'ok' : 'fehler';
                    $remoteOk = (str_contains($remoteOut, "Deleted") || str_contains($remoteOut, ":refs/tags/")) ? 'ok' : 'fehler';
                    $statusVal = ($localOk === 'ok' && $remoteOk === 'ok') ? 'gelöscht' : 'teilweise';

                    $local  = "<span class=\"local-{$localOk}\">{$localOk}</span>";
                    $remote = "<span class=\"remote-{$remoteOk}\">{$remoteOk}</span>";
                    $status = "<span class=\"status-" . ($statusVal === 'gelöscht' ? 'ok' : 'error') . "\">{$statusVal}</span>";
                }

                $rowsHtml[] =
                    "<tr>"
                    . "<td class='git-counter'>" . ($i + 1) . "</td>"
                    . "<td class='git-tags-delete-date'>" . htmlspecialchars($currentTime, ENT_QUOTES, 'UTF-8') . "</td>"
                    . "<td class='git-tags-delete-tag'>" . htmlspecialchars($t, ENT_QUOTES, 'UTF-8') . "</td>"
                    . "<td class='git-tags-delete-author'>" . htmlspecialchars($gitAuthor, ENT_QUOTES, 'UTF-8') . "</td>"
                    . "<td class='git-tags-delete-local'>" . htmlspecialchars($localOut) . "</td>"
                    . "<td class='git-tags-delete-remote'>" . htmlspecialchars($remoteOut) . "</td>"
                    . "<td class='git-tags-delete-status'>{$status}</td>"
                    . "</tr>";

                $jsonTags[] = [
                    'tag'     => $t,
                    'date'    => $currentTime,
                    'author'  => $gitAuthor,
                    'local'   => strip_tags($localOut),
                    'remote'  => strip_tags($remoteOut),
                    'status'  => strip_tags($status),
                ];
            }

            $table = "<table class='git-tags-delete-table'><thead><tr>"
                . "<th class='git-counter'>#</th>"
                . "<th class='git-tags-delete-date'>Datum</th>"
                . "<th class='git-tags-delete-tag'>Tag</th>"
                . "<th class='git-tags-delete-author'>Author</th>"
                . "<th class='git-tags-delete-local'>Lokal</th>"
                . "<th class='git-tags-delete-remote'>Remote</th>"
                . "<th class='git-tags-delete-status'>Status</th>"
                . "</tr></thead><tbody>"
                . ($rowsHtml ? implode(PHP_EOL, $rowsHtml) : "<tr><td colspan='7'>(keine Tags gefunden)</td></tr>")
                . "</tbody></table>";

            $htmlRun = "<details><summary class='git-push'><span class='run-id'>Run-ID: {$runId}</span><span class='run-id-context'>(Tag Delete)</span></summary>"
                . "<details><summary>Gelöschte Tags</summary>{$table}</details></details>";

            $existing = File::get($htmlFile);
            $pos = strrpos($existing, '<div class="backlink">');
            $updated = ($pos !== false)
                ? substr($existing, 0, $pos) . $htmlRun . substr($existing, $pos)
                : $existing . $htmlRun;
            File::put($htmlFile, $updated);

            $jsonEntry = [
                'run_id'  => $runId,
                'project' => $project,
                'user'    => $userName,
                'mode'    => 'tag-delete',
                'dry_run' => $dryRun,
                'tags'    => $jsonTags,
            ];
            File::append($jsonFile, json_encode($jsonEntry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);

            $this->info("Tag-Löschung (mit Datum + Author) ins Audit eingetragen: {$htmlFile}");
            return Command::SUCCESS;
        }

        // -------------------------------------------
        // 3. Standard Push / Commit / Tag (wiederhergestellt)
        // -------------------------------------------
        if (!$this->option('tag-list') && !$this->option('tag-delete')) {
            $this->info("[$runId] (php artisan project:git-push)");

            // --- Helper-Funktion für Git-Befehle ---
            $exec = function (string $cmd) use ($dryRun) {
                if ($dryRun) return "[dry-run] $cmd";
                $p = Process::fromShellCommandline($cmd);
                $p->setTimeout(300);
                $p->run();
                return trim($p->getOutput() . $p->getErrorOutput());
            };

            // --- Git Aktionen ---
            $statusOut = $exec('git status -s');
            $exec('git add -A');
            $addedOut  = $exec('git diff --cached --name-only');

            $commitMessage = addslashes($message);
            $commitOut = $exec("git commit -m \"{$commitMessage}\"");
            $pushOut   = $exec('git push');

            // --- Optionales Tagging ---
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

            // --- HTML-Ausgabe zusammenbauen ---
            $htmlRun = [];
            $htmlRun[] = "<details>";
            $htmlRun[] = "<summary class=\"git-push\">Run-ID: {$runId}</summary>";

            // --- Summary ---
            $htmlRun[] = "<details><summary>Summary</summary>";
            $htmlRun[] = "<table class=\"git-table\">";
            $htmlRun[] = "<tr><td class=\"git-key\">Project</td><td class=\"git-value\">{$project}</td></tr>";
            $htmlRun[] = "<tr><td class=\"git-key\">User</td><td class=\"git-value\">{$userName}</td></tr>";
            $htmlRun[] = "<tr><td class=\"git-key\">Message</td><td class=\"git-value\">" . htmlspecialchars($message) . "</td></tr>";
            if ($tag) {
                $htmlRun[] = "<tr><td class=\"git-key\">Tag</td><td class=\"git-value\">" . htmlspecialchars($tag) . "</td></tr>";
            }
            $htmlRun[] = "</table></details>";

            // --- Files ---
            $htmlRun[] = "<details><summary>Files</summary><table class=\"git-table\">";
            foreach ($sections as $key => $text) {
                $display = nl2br(htmlspecialchars($this->formatNumbered($text)));
                $htmlRun[] = "<tr><td class=\"git-key\">$key</td><td class=\"git-value\">{$display}</td></tr>";
            }
            $htmlRun[] = "</table></details>";
            $htmlRun[] = "</details>";

            $newBlock = implode(PHP_EOL, $htmlRun) . PHP_EOL;

            // --- Audit-HTML aktualisieren ---
            if (!$dryRun) {
                $existing = File::get($htmlFile);
                $pos = strrpos($existing, '<div class="backlink">');
                if ($pos !== false) {
                    $updated = substr($existing, 0, $pos) . $newBlock . substr($existing, $pos);
                    File::put($htmlFile, $updated);
                } else {
                    File::append($htmlFile, $newBlock);
                }

                // --- JSONL schreiben ---
                $jsonEntry = [
                    'run_id'  => $runId,
                    'project' => $project,
                    'user'    => $userName,
                    'message' => $message,
                    'tag'     => $tag,
                    'sections' => $sections,
                ];
                File::append($jsonFile, json_encode($jsonEntry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
            }

            $this->info("Audit aktualisiert: {$htmlFile}");
            return Command::SUCCESS;
        }
    }

    protected function formatNumbered(string $text): string
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($text));
        $lines = array_filter($lines, fn($l) => trim($l) !== '');
        if (empty($lines)) return '(keine Dateien)';
        $out = [];
        foreach ($lines as $i => $ln) $out[] = sprintf('%2d. %s', $i + 1, trim($ln));
        return implode("\n", $out);
    }
}
