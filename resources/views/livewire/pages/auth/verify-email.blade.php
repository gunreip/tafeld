<!-- tafeld/resources/views/livewire/pages/auth/verify-email.blade.php -->

<div
    class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-card text-default shadow-sm rounded-lg border border-default space-y-6">

    <h1 class="text-2xl font-semibold text-default">
        E-Mail bestätigen
    </h1>

    @if ($status === 'verification-link-sent')
        <p class="text-sm text-success">
            Ein neuer Bestätigungslink wurde gesendet.
        </p>
    @else
        <p class="text-sm text-muted">
            Bitte bestätige deine E-Mail-Adresse über den Link, den wir dir gesendet haben.
        </p>
    @endif

    <form wire:submit="resendVerification" class="space-y-6">
        <button type="submit" class="w-full py-2 flex justify-center rounded-md btn-brand">
            Link erneut senden
        </button>
    </form>

    <p class="text-sm text-center text-muted">
        Falscher Account?
        <a href="{{ route('logout') }}"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            class="text-brand-500 hover:underline">
            Abmelden
        </a>
    </p>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

</div>
