<?php

// tafeld/app/Providers/AppServiceProvider.php

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
        // Route-Helper laden (route_if_exists)
        require_once app_path('helpers/routes.php');

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useTailwind();

        // Globale Passwort-Policy
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

        // App-Settings: global setzen (nur admin / superadmin)
        Gate::define('app-settings.set-global', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
