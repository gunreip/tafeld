@if (config('tafeld-debug.view_path_comment'))
    <!-- tafeld/resources/views/livewire/pages/auth/forgot-password.blade.php -->
    <!-- {{ $__path }} -->
@endif

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <p class="text-sm text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link so you can choose a new one.') }}
    </p>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-6">

        {{-- Email --}}
        <div>
            <label for="email" class="block font-medium text-default mb-1">
                {{ __('Email') }}
            </label>

            <input wire:model="email" id="email" type="email" required autofocus
                class="w-full rounded px-3 py-2 bg-card text-default border border-default
                       focus:ring-brand-500 focus:border-brand-500" />

            @error('email')
                <p class="text-sm text-danger mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <button type="submit" class="w-full py-2 flex justify-center rounded-md btn-brand">
            {{ __('Email Password Reset Link') }}
        </button>

    </form>
</div>
