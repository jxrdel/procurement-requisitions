<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        //Gives Super Admins access for all gates
        Gate::before(function ($user, $ability) {
            if ($user->role->name === 'Super Admin') {
                return true;
            }
        });

        Gate::define('change-financial-year', function ($user) {
            return $user->role->name === 'Admin';
        });

        Gate::define('delete-records', function ($user) {
            return $user->role->name === 'Admin';
        });

        Gate::define('edit-records', function ($user) {
            return $user->role->name !== 'Viewer';
        });

        Gate::define('create-requisitions', function ($user) {
            return $user->department === 'Procurement';
        });

        Gate::define('view-procurement-requisitions', function ($user) {
            return $user->department === 'Procurement' || $user->role->name === 'Viewer';
        });

        Gate::define('view-cost-budgeting-requisitions', function ($user) {
            return $user->department === 'Cost & Budgeting' || $user->role->name === 'Viewer';
        });

        Gate::define('view-vote-control-requisitions', function ($user) {
            return $user->department === 'Vote Control' || $user->role->name === 'Viewer';
        });

        Gate::define('view-check-room-requisitions', function ($user) {
            return $user->department === 'Check Staff' || $user->role->name === 'Viewer';
        });

        Gate::define('view-cheque-processing-requisitions', function ($user) {
            return $user->department === 'Cheque Processing' || $user->role->name === 'Viewer';
        });

        Gate::define('view-users-page', function ($user) {
            return $user->role->name === 'Admin';
        });
    }
}
