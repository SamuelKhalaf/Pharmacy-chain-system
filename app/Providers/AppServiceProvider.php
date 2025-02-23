<?php

namespace App\Providers;

use App\Repositories\IAdmin;
use App\Repositories\IBranch;
use App\Repositories\ICategory;
use App\Repositories\implementation\AdminRepository;
use App\Repositories\implementation\BranchRepository;
use App\Repositories\implementation\CategoryRepository;
use App\Repositories\implementation\ProductRepository;
use App\Repositories\implementation\RoleRepository;
use App\Repositories\implementation\UserRepository;
use App\Repositories\IProduct;
use App\Repositories\IRole;
use App\Repositories\IUser;
use App\Services\IAdminService;
use App\Services\IAuthAdminService;
use App\Services\IBranchService;
use App\Services\ICategoryService;
use App\Services\implementation\AdminService;
use App\Services\implementation\AuthAdminAdminService;
use App\Services\implementation\BranchService;
use App\Services\implementation\CategoryService;
use App\Services\implementation\ProductService;
use App\Services\implementation\RoleService;
use App\Services\implementation\UserService;
use App\Services\IProductService;
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
        $this->app->bind(IAdmin::class,AdminRepository::class);
        $this->app->bind(IAdminService::class,AdminService::class);
        $this->app->bind(IRole::class,RoleRepository::class);
        $this->app->bind(IRoleService::class,RoleService::class);
        $this->app->bind(IBranch::class,BranchRepository::class);
        $this->app->bind(IBranchService::class,BranchService::class);
        $this->app->bind(IAuthAdminService::class,AuthAdminAdminService::class);
        $this->app->bind(ICategory::class,CategoryRepository::class);
        $this->app->bind(ICategoryService::class,CategoryService::class);
        $this->app->bind(IProduct::class,ProductRepository::class);
        $this->app->bind(IProductService::class,ProductService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
