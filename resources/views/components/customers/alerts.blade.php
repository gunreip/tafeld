@props(['timeout' => 5000])

{{-- Inline-Alerts für sofort sichtbare Rückmeldungen --}}
@if (session('success'))
    <div class="mb-4 rounded-md bg-green-600/10 border border-green-600/30 text-green-400 px-4 py-3">
        <p class="font-semibold">{{ session('success') }}</p>
    </div>
@endif

@if (session('error'))
    <div class="mb-4 rounded-md bg-red-600/10 border border-red-600/30 text-red-400 px-4 py-3">
        <p class="font-semibold">{{ session('error') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-600/10 border border-red-600/30 text-red-400 px-4 py-3">
        <p class="font-semibold">Bitte Eingaben prüfen:</p>
        <ul class="list-disc list-inside text-sm mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Toast-Container (dynamisch via JS) --}}
<div id="toast-container" class="fixed bottom-4 right-4 space-y-3 z-50"></div>

<script>
    document.addEventListener('toast', (e) => {
        const {
            type = 'info', message = ''
        } = e.detail || {};
        const toast = document.createElement('div');
        toast.className = `px-4 py-2 rounded shadow text-sm font-medium text-white transition-all duration-300 ${
        type === 'error' ? 'bg-red-600' :
        type === 'success' ? 'bg-green-600' :
        'bg-gray-700'
    }`;
        toast.textContent = message;
        const container = document.getElementById('toast-container');
        container.appendChild(toast);
        setTimeout(() => {
            toast.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => toast.remove(), 600);
        }, {{ $timeout }});
    });
</script>
