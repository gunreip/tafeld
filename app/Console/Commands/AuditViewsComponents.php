<?php

// tafeld/app/Console/Commands/AuditViewsComponents.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AuditViewsComponents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:views-components
                            {--view= : Scan nur eine bestimmte View (z.B. livewire/debug/logs/index)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit: Verwendete Blade- und Livewire-Komponenten pro View sowie ungenutzte Komponenten';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $viewFilter = $this->option('view');

        $viewsPath = resource_path('views');

        $views = collect(File::allFiles($viewsPath))
            ->filter(fn($file) => $file->getExtension() === 'php')
            ->map(fn($file) => Str::after($file->getPathname(), $viewsPath . DIRECTORY_SEPARATOR))
            ->values();

        if ($viewFilter) {
            $views = $views->filter(fn($view) => Str::startsWith($view, $viewFilter));
        }

        $viewUsage = [];
        $componentUsage = [];

        foreach ($views as $view) {
            $fullPath = $viewsPath . DIRECTORY_SEPARATOR . $view;
            $content  = File::get($fullPath);

            // Klassifikation: echte View vs. Blade-Komponente
            $isComponent = Str::startsWith($view, 'components/');

            // Blade-Komponenten: <x-...>
            preg_match_all('/<x-([a-zA-Z0-9\.\-_:]+)/', $content, $bladeMatches);
            $bladeComponents = collect($bladeMatches[1] ?? [])
                ->map(fn($c) => 'x-' . $c)
                ->unique()
                ->values();

            // Livewire: <livewire:...> und @livewire('...')
            preg_match_all('/<livewire:([a-zA-Z0-9\.\-_:]+)/', $content, $livewireTags);
            preg_match_all('/@livewire\\([\'"]([^\'"]+)[\'"]\\)/', $content, $livewireDirectives);

            $livewireComponents = collect()
                ->merge($livewireTags[1] ?? [])
                ->merge($livewireDirectives[1] ?? [])
                ->map(fn($c) => 'livewire:' . $c)
                ->unique()
                ->values();

            // View-Nutzung nur für echte Views erfassen
            if (!$isComponent) {
                $viewUsage[$view] = [
                    'blade'    => $bladeComponents->all(),
                    'livewire' => $livewireComponents->all(),
                ];
            }

            // Usage: Blade-Komponenten
            foreach ($bladeComponents as $component) {
                // Selbstreferenz verhindern (Komponente darf sich nicht selbst legitimieren)
                if ($isComponent) {
                    $componentPath = 'components/' . str_replace('.', '/', substr($component, 2)) . '.blade.php';
                    if ($view === $componentPath) {
                        continue;
                    }
                }

                $componentUsage[$component]['used_in'][] = $view;
            }

            // Usage: Livewire-Komponenten zählen nur von echten Views
            if (!$isComponent) {
                foreach ($livewireComponents as $component) {
                    $componentUsage[$component]['used_in'][] = $view;
                }
            }
        }

        // Karteileichen ermitteln (jetzt semantisch korrekt)
        $unusedComponents = collect($componentUsage)
            ->filter(fn($data) => empty($data['used_in']));

        // Audit-Pfad bestimmen
        $baseAuditPath = base_path('.audits/laravel/views-components');
        if ($viewFilter) {
            $baseAuditPath .= DIRECTORY_SEPARATOR . trim($viewFilter, '/');
        }

        File::ensureDirectoryExists($baseAuditPath);

        // HTML-Ausgabe (Audit)
        $totalViews    = count($viewUsage);
        $bladeCount    = collect($componentUsage)->keys()->filter(fn($c) => str_starts_with($c, 'x-'))->count();
        $livewireCount = collect($componentUsage)->keys()->filter(fn($c) => str_starts_with($c, 'livewire:'))->count();
        $unusedCount   = $unusedComponents->count();

        $html  = '<!DOCTYPE html><html lang="de"><head>';
        $html .= '<meta charset="UTF-8">';
        $html .= '<title>Views & Components Audit</title>';
        $html .= '</head><body style="font-family: monospace">';

        $html .= '<h1>Views & Components Audit</h1>';

        // Summary
        $html .= '<details open>';
        $html .= '<summary><strong>Summary</strong></summary>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0">';
        $html .= '<tr><th>Views</th><th>Blade-Komponenten</th><th>Livewire-Komponenten</th><th>Karteileichen</th></tr>';
        $html .= '<tr>';
        $html .= "<td>{$totalViews}</td>";
        $html .= "<td>{$bladeCount}</td>";
        $html .= "<td>{$livewireCount}</td>";
        $html .= "<td>{$unusedCount}</td>";
        $html .= '</tr></table>';
        $html .= '</details>';

        // Views -> benutzte Komponenten
        $html .= '<details>';
        $html .= '<summary><strong>Views → benutzte Komponenten</strong></summary>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0">';
        $html .= '<tr><th>View</th><th>Komponenten</th></tr>';

        $sortedViews = collect($viewUsage)->sortKeys();
        foreach ($sortedViews as $view => $data) {
            $components = collect(array_merge($data['blade'], $data['livewire']))
                ->sort()
                ->groupBy(fn($component) => substr($component, 0, strrpos($component, '.')));

            $html .= '<tr>';
            $html .= '<td>' . $view . '</td>';
            $html .= '<td>';
            foreach ($components as $namespace => $items) {
                $html .= '<strong>' . $namespace . '</strong><br>';
                foreach ($items as $component) {
                    $html .= $component . '<br>';
                }
                $html .= '<br>';
            }
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '</table></details>';

        // Komponenten -> benutzte Views (Namespace-Gruppenkopf)
        $html .= '<details>';
        $html .= '<summary><strong>Komponenten → benutzte Views</strong></summary>';
        $html .= '<table border="1" cellpadding="6" cellspacing="0">';
        $html .= '<tr><th>Komponente</th><th>Views</th></tr>';

        $sortedComponents = collect($componentUsage)
            ->map(fn($data, $componentName) => [
                'name'    => $componentName,
                'used_in' => $data['used_in'] ?? [],
            ])
            ->sortBy('name')
            ->groupBy(fn($item) => substr($item['name'], 0, strrpos($item['name'], '.')));

        foreach ($sortedComponents as $namespace => $components) {
            $html .= '<tr><td colspan="2"><strong>' . $namespace . '</strong></td></tr>';

            foreach ($components as $item) {
                $viewsList = collect($item['used_in'])->sort();
                $html .= '<tr>';
                $html .= '<td style="padding-left: 20px;">' . $item['name'] . '</td>';
                $html .= '<td>' . implode('<br>', $viewsList->all()) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table></details>';

        // Karteileichen
        $html .= '<details>';
        $html .= '<summary><strong>Karteileichen (ungenutzte Komponenten)</strong></summary>';
        $html .= '<ul>';
        foreach ($unusedComponents as $component => $_) {
            $html .= '<li>' . $component . '</li>';
        }
        $html .= '</ul></details>';

        $html .= '</body></html>';

        File::put($baseAuditPath . DIRECTORY_SEPARATOR . 'index.html', $html);

        return self::SUCCESS;
    }
}
