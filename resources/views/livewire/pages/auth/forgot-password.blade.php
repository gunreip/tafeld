<!-- tafeld/resources/views/livewire/pages/auth/forgot-password.blade.php -->

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <div class="text-sm text-muted">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    {{-- Session Status --}}
    @if (session('status'))
        <div class="text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <form wire:submit="sendPasswordResetLink" class="space-y-4">

        {{-- Email --}}
        <div class="space-y-2">
            <label for="email" class="block text-sm font-medium text-default">
                {{ __('Email') }}
            </label>

            <input wire:model="email" id="email" type="email" name="email" required autofocus
                class="block w-full rounded-md bg-card text-default border border-default shadow-sm
                       focus:ring-brand-500 focus:border-brand-500">

            @error('email')
                <p class="text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex items-center justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 btn-brand">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>

    </form>
</div>
