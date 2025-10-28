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
    {--tag-list : Listet vorhandene Git-Tags und protokolliert sie im Audit}';
    protected $description = 'Commit, Push oder Tag für das aktuelle Projekt erstellen, mit Monatsrotation und HTML/JSON-Audit-Output.';

    public function handle(): int
    {
        $dryRun   = (bool)$this->option('dry-run');
        $tag      = $this->option('tag');
        $message  = $this->option('message') ?: ('auto push ' . now()->format('Y-m-d H:i:s'));
        $project  = basename(base_path());
        $userName = trim(shell_exec('whoami')) ?: 'unknown';
        $runId    = now()->format('Y-m-d H:i:s');
        $laravel  = app()->version();
        $php      = PHP_VERSION;
        $context  = 'git'; // dynamischer Audit-Kontext

        // -------------------------------------------
        // 1. Monatsrotation und Datei-Struktur prüfen
        // -------------------------------------------
        $yearDir   = base_path('.logs/audits/' . $context . '/' . now()->format('Y'));
        $monthFile = now()->format('Y-m');
        $htmlFile  = "{$yearDir}/{$monthFile}-{$context}.html";
        $jsonFile  = "{$yearDir}/{$monthFile}-{$context}.jsonl";

        File::ensureDirectoryExists($yearDir);

        // Template-Dateien einlesen
        $headerTemplate = base_path('.logs/template-header.html');
        $footerTemplate = base_path('.logs/template-footer.html');

        // --- Beschreibungsdatei auslesen (kontextabhängig) ---
        $descFile = base_path(".logs/audits/{$context}/audit-main-desc.txt");
        $subtitleContent = File::exists($descFile) ? File::get($descFile) : '(no description)';

        $header = File::exists($headerTemplate) ? File::get($headerTemplate) : '';
        $footer = File::exists($footerTemplate) ? File::get($footerTemplate) : '';

        // Platzhalter und CSS-Pfade ersetzen, falls Datei neu erstellt wird
        if (!File::exists($htmlFile)) {
            // --- dynamische CSS-Links ---
            $baseCss = '../../../audits-base.css';
            $ctxCss  = "../../../audits-{$context}.css";
            $header = str_replace(
                ['../../audits-base.css', '../../audits-git.css', '../../../../audits-base.css', '../../../../audits-git.css'],
                [$baseCss, $ctxCss, $baseCss, $ctxCss],
                $header
            );

            // --- Backlink-Pfade korrigieren ---
            $header = str_replace('../../audits-main.html', '../../../audits-main.html', $header);
            $footer = str_replace('../../audits-main.html', '../../../audits-main.html', $footer);

            // --- Oberen Backlink nach Subtitle einfügen ---
            $insertPos = strpos($header, '</span>');
            if ($insertPos !== false) {
                $header = substr_replace(
                    $header,
                    "\n<div class=\"backlink\"><a href=\"../../../audits-main.html\">Back to Audits-Main</a></div>\n",
                    $insertPos + 7,
                    0
                );
            }

            // --- Platzhalter ersetzen ---
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

            if (!$dryRun) {
                File::put($htmlFile, $header . $footer);
                File::put($jsonFile, '');
            }
            $this->info("Neue Monats-Auditdateien erstellt: {$htmlFile}, {$jsonFile}");
        }

        // -------------------------------------------
        // 2. Git-Operationen ausführen
        // -------------------------------------------
        $this->info("[$runId] (php artisan project:git-push)");

        $exec = function (string $cmd) use ($dryRun) {
            if ($dryRun) {
                return "[dry-run] $cmd";
            }
            $p = Process::fromShellCommandline($cmd);
            $p->setTimeout(300);
            $p->run();
            return trim($p->getOutput() . $p->getErrorOutput());
        };

        $statusOut = $exec('git status -s');
        $exec('git add -A');
        $addedOut  = $exec('git diff --cached --name-only');
        $commitOut = $exec('git commit -m "' . addslashes($message) . '"');
        $pushOut   = $exec('git push');

        $tagOut = '';
        if ($tag) {
            $exec("git tag -a $tag -m \"" . addslashes($message ?: 'Checkpoint') . "\"");
            $tagOut = $exec("git push origin $tag");
        }

        // -------------------------------------------
        // 2a. Git-Tag-Liste ausgeben (optional)
        // -------------------------------------------
        if ($this->option('tag-list')) {
            $this->info("[$runId] (php artisan project:git-push --tag-list)");

            $exec = function (string $cmd) use ($dryRun) {
                if ($dryRun) return "[dry-run] $cmd";
                $p = Process::fromShellCommandline($cmd);
                $p->setTimeout(300);
                $p->run();
                return trim($p->getOutput() . $p->getErrorOutput());
            };

            // Tags mit Datum | Tag | Subject | Author
            $raw   = $exec("git tag --list --format='%(creatordate:iso) | %(tag) | %(subject) | %(taggername)'");
            $lines = array_values(array_filter(preg_split("/\r\n|\n|\r/", $raw), fn($l) => trim($l) !== ''));

            // Tabelle bauen
            $rowsHtml = [];
            $jsonTags = [];
            foreach ($lines as $i => $ln) {
                [$date, $tagName, $subject, $author] = array_map('trim', explode('|', $ln, 4)) + ['', '', '', ''];
                $rowsHtml[] =
                    '<tr>'
                    . '<td class="git-counter">' . ($i + 1) . '</td>'
                    . '<td class="git-date">'    . htmlspecialchars($date,    ENT_QUOTES, 'UTF-8') . '</td>'
                    . '<td class="git-tag">'     . htmlspecialchars($tagName, ENT_QUOTES, 'UTF-8') . '</td>'
                    . '<td class="git-message">' . htmlspecialchars($subject, ENT_QUOTES, 'UTF-8') . '</td>'
                    . '<td class="git-author">'  . htmlspecialchars($author,  ENT_QUOTES, 'UTF-8') . '</td>'
                    . '</tr>';

                $jsonTags[] = ['date' => $date, 'tag' => $tagName, 'subject' => $subject, 'author' => $author];
            }

            $tableHtml = [];
            $tableHtml[] = '<table class="git-table">';
            $tableHtml[] = '<thead><tr><th>#</th><th>Datum</th><th>Tag</th><th>Message</th><th>Autor</th></tr></thead>';
            $tableHtml[] = '<tbody>';
            $tableHtml[] = $rowsHtml ? implode(PHP_EOL, $rowsHtml) : '<tr><td colspan="5">(keine Tags gefunden)</td></tr>';
            $tableHtml[] = '</tbody></table>';

            // Audit-Block
            $htmlRun = [];
            $htmlRun[] = '<details>';
            $htmlRun[] = '<summary class="git-push">Run-ID: ' . $runId . ' (Tag List)</summary>';
            $htmlRun[] = '<details><summary>Tags</summary>' . implode(PHP_EOL, $tableHtml) . '</details>';
            $htmlRun[] = '</details>';

            $newBlock = implode(PHP_EOL, $htmlRun) . PHP_EOL;

            if (!$dryRun) {
                // Vor dem Backlink einfügen
                $existing = File::get($htmlFile);
                $pos = strrpos($existing, '<div class="backlink">');
                if ($pos !== false) {
                    $updated = substr($existing, 0, $pos) . $newBlock . substr($existing, $pos);
                    File::put($htmlFile, $updated);
                } else {
                    File::append($htmlFile, $newBlock);
                }

                // JSONL
                $jsonEntry = [
                    'run_id'  => $runId,
                    'project' => $project,
                    'user'    => $userName,
                    'mode'    => 'tag-list',
                    'tags'    => $jsonTags,
                ];
                File::append($jsonFile, json_encode($jsonEntry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . PHP_EOL);
            }

            $this->info("Tag-Liste (Tabelle) ins Audit eingetragen: {$htmlFile}");
            return Command::SUCCESS;
        }

        // -------------------------------------------
        // 3. Audit-Block vorbereiten
        // -------------------------------------------
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

        // -------------------------------------------
        // 4. Audit-HTML aktualisieren (vor Backlink)
        // -------------------------------------------
        if (!$dryRun) {
            $existing = File::get($htmlFile);
            $pos = strrpos($existing, '<div class="backlink">');

            if ($pos !== false) {
                $updated = substr($existing, 0, $pos) . $newBlock . substr($existing, $pos);
                File::put($htmlFile, $updated);
            } else {
                File::append($htmlFile, $newBlock);
            }

            // JSONL-Eintrag
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
