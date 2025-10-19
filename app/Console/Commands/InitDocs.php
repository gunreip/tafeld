<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InitDocs extends Command
{
    protected $signature = 'project:init-docs';
    protected $description = 'Erzeugt die empfohlene Dokumentationsstruktur unter docs/.';

    public function handle(): int
    {
        $docsPath = base_path('docs');
        File::ensureDirectoryExists($docsPath);

        $files = [
            'project.md'   => "# Projektbeschreibung\n\nKurze Übersicht über Ziele, Kontext, Versionierung.",
            'setup.md'     => "# Setup\n\nInstallations- und Deployment-Schritte.",
            'structure.md' => "# Struktur\n\nAusgabe von project:structure oder manuelle Notizen zur Verzeichnisstruktur.",
            'config.md'    => "# Konfiguration\n\nErklärung zu .env, Config-Files und Packages.",
            'routes.md'    => "# Routen\n\nAPI- und Web-Routen dokumentieren.",
            'commands.md'  => "# Artisan Commands\n\nEigene Konsolenbefehle dokumentieren.",
            'changelog.md' => "# Changelog\n\nÄnderungen, Versionen, Datum.",
        ];

        foreach ($files as $name => $content) {
            $filePath = $docsPath . DIRECTORY_SEPARATOR . $name;
            if (!File::exists($filePath)) {
                File::put($filePath, $content . "\n");
                $this->info("Erstellt: {$filePath}");
            } else {
                $this->line("Übersprungen (existiert): {$filePath}");
            }
        }

        $this->info('Dokumentationsstruktur initialisiert.');
        return Command::SUCCESS;
    }
}
