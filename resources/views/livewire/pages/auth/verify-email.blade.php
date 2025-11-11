<div class="w-full sm:max-w-md mx-auto mt-10 px-6 py-8 bg-white dark:bg-gray-800 shadow-lg rounded-lg">

    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-6">
        E-Mail bestätigen
    </h1>

    @if ($status === 'verification-link-sent')
        <p class="mb-4 text-sm text-green-600 dark:text-green-400">
            Ein neuer Bestätigungslink wurde gesendet.
        </p>
    @else
        <p class="mb-4 text-sm text-gray-700 dark:text-gray-300">
            Bitte bestätige deine E-Mail-Adresse über den Link, den wir dir gesendet haben.
        </p>
    @endif

    <form wire:submit="resendVerification" class="space-y-6">
        <button type="submit"
            class="w-full py-2 flex justify-center rounded-md bg-indigo-600 text-white
                       hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600
                       focus:ring-2 focus:ring-indigo-500">
            Link erneut senden
        </button>
    </form>

    <p class="text-sm text-center text-gray-600 dark:text-gray-400 mt-4">
        Falscher Account?
        <a href="{{ route('logout') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            Abmelden
        </a>
    </p>

    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

</div>
