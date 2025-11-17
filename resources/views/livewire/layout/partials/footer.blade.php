<!-- tafeld/resources/views/livewire/layout/partials/footer.blade.php -->

<footer class="border-t border-default bg-elevated backdrop-blur">
    <div class="px-4 lg:px-6 py-3 flex items-center justify-between text-xs text-muted">
        <span>&copy; {{ date('Y') }} {{ config('app.name', 'tafeld') }}</span>

        <span class="inline-flex items-center gap-1">
            <span class="hidden sm:inline text-muted">Status:</span>
            <span class="font-mono text-default">livewire</span>
        </span>
    </div>
</footer>
