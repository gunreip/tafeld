<div>
    @props(['value' => '', 'label' => 'Vorname', 'name' => 'first_name', 'icon' => 'person'])

    <div class="flex flex-col mb-3">
        <label for="{{ $name }}" class="flex items-center gap-2 text-sm font-semibold text-gray-200">
            <ion-icon name="{{ $icon }}" class="w-4 h-4 text-gray-400"></ion-icon>
            {{ __($label) }}
        </label>

        <input type="text" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $value) }}"
            class="rounded-md bg-gray-800 text-gray-100 border {{ $errors->has($name) ? 'border-red-500' : 'border-gray-700' }}
               focus:border-blue-400 focus:ring-blue-400 px-3 py-2">

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
