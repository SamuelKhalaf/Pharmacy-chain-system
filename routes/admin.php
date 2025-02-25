<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthAdminController;
use App\Http\Controllers\admin\BranchController;
use App\Http\Controllers\admin\BranchInventoryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TransferProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/start', function () {
    return view('welcome');
});


Route::group(['prefix' => 'dashboard' , 'middleware' => 'auth:admin'] , function () {
    // dashboard
    Route::get('/' , [DashboardController::class, 'index'])->name('home');

    // admin logout
    Route::post('/admin/logout', [AuthAdminController::class, 'logout'])->name('admin.logout');

    // CRUD ADMIN
    Route::resource('admin', AdminController::class);

    // CRUD CATEGORY
    Route::resource('category', CategoryController::class)->except('show');

    // CRUD PRODUCT
    Route::resource('product', ProductController::class);

    // CRUD BRANCHES
    Route::resource('branch', BranchController::class);

    // CRUD BranchInventory
    Route::get('inventory', [BranchInventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/create', [BranchInventoryController::class, 'create'])->name('inventory.create');
    Route::post('inventory', [BranchInventoryController::class, 'store'])->name('inventory.store');
    Route::get('inventory/{branch}/{product}', [BranchInventoryController::class, 'show'])->name('inventory.show');
    Route::get('inventory/{branch}/{product}/edit', [BranchInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{branch}/{product}', [BranchInventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{branch}/{product}', [BranchInventoryController::class, 'destroy'])->name('inventory.destroy');

//    Route::resource('inventory', BranchInventoryController::class);
    // ajax
    Route::get('allInventoryProducts', [BranchInventoryController::class,'allInventoryProducts'])->name('allInventoryProducts.ajax');

    Route::get('transfer-products',[TransferProductsController::class,'showTransferForm'])->name('products.transfer');
});

Route::group(['prefix' => 'admin'] , function () {
    // admin login
    Route::get('/login', [AuthAdminController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdminController::class, 'adminLogin'])->name('admin.post.login');

});


