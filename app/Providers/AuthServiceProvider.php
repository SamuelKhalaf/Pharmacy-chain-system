<?php

namespace App\Providers;

use App\Enums\AdminRole;
use App\Models\Admin;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('super_admin', function ($auth) {
            return  $auth->role_id === AdminRole::SuperAdmin->value;
        });

        Gate::define('branch_admin', function ($auth) {
            return  $auth->role_id === AdminRole::BranchAdmin->value;
        });
    }
}
