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
            return $user->role->name !== 'Viewer' && $user->role->name !== 'Requisition Form User';
        });

        Gate::define('create-requisitions', function ($user, $form) {
            return $user->department->name === 'Procurement Unit' && $form->procurement_approval;
        });

        Gate::define('view-procurement-requisitions', function ($user) {
            return $user->department->name === 'Procurement Unit' || ($user->role->name !== 'Requisition Form User');
        });

        Gate::define('view-requisition', function ($user, $requisition) {
            return $user->department->name === 'Procurement Unit' || ($user->role->name === 'Viewer') || $user->department->id == $requisition->requesting_unit;
        });

        Gate::define('view-accounts-payable-requisitions', function ($user) {
            return $user->department->name === 'Accounts Payable' || ($user->role->name === 'Viewer' && $user->department->name === 'Office of the Permanent Secretary');
        });

        Gate::define('view-cost-budgeting-requisitions', function ($user) {
            return $user->department->name === 'Cost & Budgeting' || ($user->role->name === 'Viewer' && $user->department->name === 'Office of the Permanent Secretary');
        });

        Gate::define('view-vote-control-requisitions', function ($user) {
            return $user->department->name === 'Vote Control' || ($user->role->name === 'Viewer' && $user->department->name === 'Office of the Permanent Secretary');
        });

        Gate::define('view-check-room-requisitions', function ($user) {
            return $user->department->name === 'Check Staff' || ($user->role->name === 'Viewer' && $user->department->name === 'Office of the Permanent Secretary');
        });

        Gate::define('view-cheque-processing-requisitions', function ($user) {
            return $user->department->name === 'Cheque Processing' || ($user->role->name === 'Viewer' && $user->department->name === 'Office of the Permanent Secretary');
        });

        Gate::define('view-users-page', function ($user) {
            return $user->role->name === 'Admin';
        });

        Gate::define('view-votes-page', function ($user) {
            return $user->role->name === 'Admin';
        });

        Gate::define('view-log', function ($user, $log) {
            return $user->department->name === $log->user->department->name;
        });

        Gate::define('view-requisition-form', function ($user, $form) {
            return $user->id == $form->contact_person_id || $user->id == $form->head_of_department_id || $user->is_reporting_officer || $user->department->name === 'Procurement Unit';
        });

        Gate::define('edit-requisition-form', function ($user, $form) {
            return $user->id == $form->contact_person_id || $user->id == $form->head_of_department_id;
        });
    }
}
