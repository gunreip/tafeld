<div>
    @if (session('success'))
        <div class="toast toast-end toast-top">
            <div class="alert alert-success shadow-lg">
                <x-heroicon-s-check-circle class="w-5 h-5" />
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="toast toast-end toast-top">
            <div class="alert alert-error shadow-lg">
                <x-heroicon-s-x-circle class="w-5 h-5" />
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif
</div>
