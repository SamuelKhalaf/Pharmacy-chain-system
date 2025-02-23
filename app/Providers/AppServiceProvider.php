<?php

namespace App\Providers;

use App\Repositories\IBranch;
use App\Repositories\implementation\BranchRepository;
use App\Repositories\implementation\RoleRepository;
use App\Repositories\implementation\UserRepository;
use App\Repositories\IRole;
use App\Repositories\IUser;
use App\Services\IBranchService;
use App\Services\implementation\BranchService;
use App\Services\implementation\RoleService;
use App\Services\implementation\UserService;
use App\Services\IRoleService;
use App\Services\IUserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IRole::class,RoleRepository::class);
        $this->app->bind(IRoleService::class,RoleService::class);
        $this->app->bind(IBranch::class,BranchRepository::class);
        $this->app->bind(IBranchService::class,BranchService::class);
        $this->app->bind(IUser::class,UserRepository::class);
        $this->app->bind(IUserService::class,UserService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
