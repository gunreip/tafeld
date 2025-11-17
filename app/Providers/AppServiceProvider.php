<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Globale Passwort-Policy (gilt in allen FormRequests/Validatoren mit Password::defaults())
        Password::defaults(function () {
            return Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols();
        });

        // SuperAdmin darf alles
        Gate::before(function ($user, string $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });
    }
}
