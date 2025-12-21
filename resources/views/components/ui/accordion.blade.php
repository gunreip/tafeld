{{-- tafeld/resources/views/components/ui/accordion.blade.php --}}

@props([
    'multiple' => false, // true = mehrere gleichzeitig offen
])

<div x-data="{
    openItems: {},
    toggle(id) {
        if (!{{ $multiple ? 'true' : 'false' }}) {
            // Alle schließen, wenn multiple=false
            this.openItems = {};
        }
        this.openItems[id] = !this.openItems[id];
    },
    isOpen(id) {
        return !!this.openItems[id];
    }
}" class="space-y-3">
    {{ $slot }}
</div>
