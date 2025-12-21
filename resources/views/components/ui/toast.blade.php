{{-- tafeld/resources/views/components/ui/toast.blade.php --}}

@props([
    'position' => 'top-right', // top-right | top-left | bottom-right | bottom-left
    'timeout' => 3500, // Auto-Dismiss in ms
])

@php
    $pos = [
        'top-right' => 'top-4 right-4',
        'top-left' => 'top-4 left-4',
        'bottom-right' => 'bottom-4 right-4',
        'bottom-left' => 'bottom-4 left-4',
    ][$position];
@endphp

<div x-data="toastManager({ timeout: {{ $timeout }} })" class="fixed z-[9999] {{ $pos }} space-y-3" x-on:toast.window="add($event.detail)">
    <template x-for="(t, index) in toasts" :key="t.id">
        <div x-show="t.visible" x-transition:enter="transition transform ease-out duration-200"
            x-transition:enter-start="translate-y-2 opacity-0 scale-95"
            x-transition:enter-end="translate-y-0 opacity-100 scale-100"
            x-transition:leave="transition transform ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
            class="flex items-start gap-3 px-4 py-3 min-w-[260px] rounded-lg shadow-lg border border-default bg-card text-default">
            {{-- Icon --}}
            <div x-html="icon(t.type)" class="mt-0.5"></div>

            {{-- Text --}}
            <div class="flex-1 text-sm" x-text="t.message"></div>

            {{-- Close --}}
            <button @click="remove(t.id)" class="text-muted hover:text-default">
                ✕
            </button>
        </div>
    </template>
</div>


<script>
    function toastManager({
        timeout = 3500
    }) {
        return {
            toasts: [],

            add({
                type = 'info',
                message = ''
            }) {
                const id = Date.now() + Math.random();

                this.toasts.push({
                    id,
                    type,
                    message,
                    visible: true
                });

                setTimeout(() => this.remove(id), timeout);
            },

            remove(id) {
                const t = this.toasts.find(x => x.id === id);
                if (!t) return;

                t.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(x => x.id !== id);
                }, 200);
            },

            icon(type) {
                switch (type) {
                    case 'success':
                        return `<span class='text-success text-lg'>✔</span>`;
                    case 'error':
                        return `<span class='text-danger text-lg'>⚠</span>`;
                    case 'warning':
                        return `<span class='text-warning text-lg'>⚠</span>`;
                    default:
                        return `<span class='text-info text-lg'>ℹ</span>`;
                }
            }
        };
    }
</script>
