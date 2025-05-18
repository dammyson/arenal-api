<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\ServiceProvider;

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
        Gate::define('is-user', function (User $user) {
            return !$user->is_audience
                ? Response::allow()
                : Response::deny('You must be a user');
        });

        Gate::define('is-audience', function (User $user) {
            return $user->is_audience
                ? Response::allow()
                : Response::deny('You must be an audience');
        });

    }
}
