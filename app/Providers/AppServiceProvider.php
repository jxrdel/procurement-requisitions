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
        //Gives ICT access for all gates
        Gate::before(function ($user, $ability) {
            if ($user->department === 'ICT') {
                return true;
            }
        });

        Gate::define('view-cost-budgeting-requisition', function ($user) {
            return $user->department === 'Cost & Budgeting';
        });

        Gate::define('view-cheque-dispatch-requisition', function ($user) {
            return $user->department === 'Cheque Dispatch';
        });
    }
}
