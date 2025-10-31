<div>
    @props([
        'value' => '',
        'label' => 'Personalausweis-Dokument',
        'name' => 'identity_document',
        'icon' => 'id-card',
    ])

    <div x-data="{ fileName: '' }" class="flex flex-col mb-3">
        {{-- Label + Icon --}}
        <label for="{{ $name }}" class="flex items-center gap-2 text-sm font-semibold text-gray-200">
            <ion-icon name="{{ $icon }}" class="w-4 h-4 text-gray-400"></ion-icon>
            {{ __($label) }}
        </label>

        {{-- Datei-Upload --}}
        <input type="file" id="{{ $name }}" name="{{ $name }}"
            @change="fileName = $event.target.files[0]?.name || ''"
            class="block w-full text-sm text-gray-200
               file:mr-3 file:py-2 file:px-4
               file:rounded-md file:border-0
               file:text-sm file:font-semibold
               file:bg-blue-600 file:text-white
               hover:file:bg-blue-500
               rounded-md bg-gray-800 border
               {{ $errors->has($name) ? 'border-red-500' : 'border-gray-700' }}
               focus:border-blue-400 focus:ring-blue-400">

        {{-- Dateiname anzeigen --}}
        <p x-show="fileName" x-text="fileName" class="mt-2 text-xs text-gray-400 truncate"></p>

        {{-- Vorhandene Datei anzeigen --}}
        @if (!empty($value))
            <div class="mt-2">
                <a href="{{ Storage::url($value) }}" target="_blank" class="text-blue-400 text-xs underline">
                    Vorhandene Datei ansehen
                </a>
            </div>
        @endif

        {{-- Fehleranzeige --}}
        @error($name)
            <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
            <script>
                window.dispatchEvent(new CustomEvent('toast', {
                    detail: {
                        type: 'error',
                        message: @js($message)
                    }
                }));
            </script>
        @enderror
    </div>
</div>
