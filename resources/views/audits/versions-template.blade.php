{{-- tafeld/resources/views/audits/versions-template.blade.php --}}

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Versions-Audit</title>

    {{-- Tailwind-CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="text-sm p-6 bg-gray-50">

    <h1 class="text-2xl font-semibold mb-6">Versions-Audit</h1>

    {{-- Summary --}}
    <details class="mb-8" open>
        <summary class="cursor-pointer text-lg mb-2 font-semibold">Summary</summary>

        <table class="audit-table w-full border border-gray-300 rounded-lg shadow-sm text-sm">
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">PHP</td>
                <td class="value-text p-2">{{ $summary['php'] }}</td>
            </tr>
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">Laravel</td>
                <td class="value-text p-2">{{ $summary['laravel'] }}</td>
            </tr>
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">Node</td>
                <td class="value-text p-2">{{ $summary['node'] }}</td>
            </tr>
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">npm</td>
                <td class="value-text p-2">{{ $summary['npm'] }}</td>
            </tr>
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">Composer-Pakete</td>
                <td class="value-numeric p-2">{{ $summary['composer_count'] }}</td>
            </tr>
            <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50">
                <td class="value-key p-2 font-semibold">npm-Pakete</td>
                <td class="value-numeric p-2">{{ $summary['npm_count'] }}</td>
            </tr>
        </table>
    </details>

    {{-- Composer Packages --}}
    <details class="mb-8">
        <summary class="cursor-pointer text-lg mb-2 font-semibold">
            Composer Packages <span class="text-gray-400 text-xs">(sorted)</span>
        </summary>

        <table class="audit-table w-full border border-gray-300 rounded-lg shadow-sm text-sm">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th class="text-left p-2 w-2/12">Paket</th>
                    <th class="text-left p-2 w-2/12">Version</th>
                    <th class="text-left p-2 w-3/12">Abhängigkeiten</th>
                    <th class="text-left p-2 w-4/12">Beschreibung</th>
                    <th class="text-left p-2 w-1/12">Lizenz</th>
                </tr>
            </thead>
            <tbody>
                @foreach($composer as $pkg)
                    <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50 transition-colors">
                        <td class="font-mono value-text p-2">{{ $pkg['name'] }}</td>

                        {{-- Version + Badge --}}
                        <td class="value-text p-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono">{{ $pkg['version'] }}</span>

                                @if(isset($pkg['update_type']) && $pkg['update_type'] !== 'none')
                                    <span class="
                                        px-2 py-0.5 rounded text-xs font-mono font-semibold
                                        @if($pkg['update_type'] === 'major') bg-red-200 text-red-700
                                        @elseif($pkg['update_type'] === 'minor') bg-yellow-200 text-yellow-700
                                        @elseif($pkg['update_type'] === 'patch') bg-green-200 text-green-700
                                        @endif
                                    ">
                                        ↑ {{ $pkg['version_latest'] ?? '' }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Dependencies in <details> --}}
                        <td class="value-text p-2">
                            @if(isset($pkg['deps']) && count($pkg['deps']) > 0)
                                <details class="text-sm">
                                    <summary class="cursor-pointer font-mono">&gt; {{ count($pkg['deps']) }}</summary>
                                    <ul class="ml-4 mt-1 list-disc text-xs text-gray-600 space-y-1">
                                        @foreach($pkg['deps'] as $dep)
                                            <li class="font-mono">
                                                {{ $dep['name'] }} ({{ $dep['version'] }})
                                                @if(isset($dep['sub_count']) && $dep['sub_count'] > 0)
                                                    <span class="ml-2 px-1 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px]">
                                                        → {{ $dep['sub_count'] }} weitere
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            @endif
                        </td>

                        <td class="value-text p-2">{{ $pkg['description'] ?? '' }}</td>
                        <td class="value-text p-2">
                            @if(isset($pkg['license']))
                                {{ is_array($pkg['license']) ? implode(', ', $pkg['license']) : $pkg['license'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </details>

    {{-- npm Packages --}}
    <details class="mb-8">
        <summary class="cursor-pointer text-lg mb-2 font-semibold">
            npm Packages (Top-Level) <span class="text-gray-400 text-xs">(sorted)</span>
        </summary>

        <table class="audit-table w-full border border-gray-300 rounded-lg shadow-sm text-sm">
            <thead class="bg-gray-100 sticky top-0 z-10">
                <tr>
                    <th class="text-left p-2 w-2/6">Paket</th>
                    <th class="text-left p-2 w-1/6">Version</th>
                    <th class="text-left p-2 w-3/6">Abhängigkeiten</th>
                </tr>
            </thead>
            <tbody>
                @foreach($npm as $pkg)
                    <tr class="hover:bg-gray-50 odd:bg-white even:bg-gray-50/50 transition-colors">
                        <td class="font-mono value-text p-2">{{ $pkg['name'] }}</td>

                        {{-- Version + Badge --}}
                        <td class="value-text p-2">
                            <div class="flex items-center gap-2">
                                <span class="font-mono">{{ $pkg['version'] }}</span>

                                @if(isset($pkg['update_type']) && $pkg['update_type'] !== 'none')
                                    <span class="
                                        px-2 py-0.5 rounded text-xs font-semibold
                                        @if($pkg['update_type'] === 'major') bg-red-200 text-red-700
                                        @elseif($pkg['update_type'] === 'minor') bg-yellow-200 text-yellow-700
                                        @elseif($pkg['update_type'] === 'patch') bg-green-200 text-green-700
                                        @endif
                                    ">
                                        ↑ {{ $pkg['version_latest'] ?? '' }}
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Dependencies in <details> --}}
                        <td class="value-text p-2">
                            @if(isset($pkg['deps']) && count($pkg['deps']) > 0)
                                <details class="text-sm">
                                    <summary class="cursor-pointer font-mono">&gt; {{ count($pkg['deps']) }}</summary>
                                    <ul class="ml-4 mt-1 list-disc text-xs text-gray-600 space-y-1">
                                        @foreach($pkg['deps'] as $dep)
                                            <li class="font-mono">
                                                {{ $dep['name'] }} ({{ $dep['version'] }})
                                                @if(isset($dep['sub_count']) && $dep['sub_count'] > 0)
                                                    <span class="ml-2 px-1 py-0.5 rounded bg-blue-100 text-blue-700 text-[10px]">
                                                        → {{ $dep['sub_count'] }} weitere
                                                    </span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </details>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </details>

</body>
</html>
