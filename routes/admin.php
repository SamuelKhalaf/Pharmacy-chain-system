<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthAdminController;
use App\Http\Controllers\admin\BranchController;
use App\Http\Controllers\admin\BranchInventoryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\TransferRequestController;
use App\Http\Controllers\admin\TransferProductsController;
use App\Models\TransferRequest;
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

    ########################### Start CRUD BranchInventory #################################
    Route::get('inventory', [BranchInventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/create', [BranchInventoryController::class, 'create'])->name('inventory.create');
    Route::post('inventory', [BranchInventoryController::class, 'store'])->name('inventory.store');
    Route::get('inventory/{branch}/{product}', [BranchInventoryController::class, 'show'])->name('inventory.show');
    Route::get('inventory/{branch}/{product}/edit', [BranchInventoryController::class, 'edit'])->name('inventory.edit');
    Route::put('inventory/{branch}/{product}', [BranchInventoryController::class, 'update'])->name('inventory.update');
    Route::delete('inventory/{branch}/{product}', [BranchInventoryController::class, 'destroy'])->name('inventory.destroy');
    // BranchInventory ajax
    Route::get('inventory/allInventoryProducts', [BranchInventoryController::class,'allInventoryProducts'])->name('allInventoryProducts.ajax');
    ########################### End CRUD BranchInventory ###################################

    ########################### Start Transfer Products #################################
    Route::get('/transfer',[TransferProductsController::class,'showTransferForm'])->name('transfer.show-transfer-form');
    Route::get('/transfer/get-products',[TransferProductsController::class,'getSpecificBranchProducts'])->name('transfer.get-products');
    Route::post('/transfer/store',[TransferProductsController::class,'storeTransferredProducts'])->name('transfer.store');
    ########################### End Transfer Products #################################

    ########################### Start Request Products #################################
    Route::get('/request',[TransferRequestController::class,'index'])->name('request.index');
    Route::get('/request/make',[TransferRequestController::class,'showRequestForm'])->name('request.show-request-form');
    Route::get('/request/get-products',[TransferRequestController::class,'getSpecificBranchProducts'])->name('request.get-products');
    Route::post('/request/store',[TransferRequestController::class,'storeRequest'])->name('request.store');
    Route::post('/request/{id}/accept',[TransferRequestController::class,'acceptRequest'])->name('request.accept');
    Route::post('/request/{id}/cancel',[TransferRequestController::class,'cancelRequest'])->name('request.cancel');
    Route::get('/requests/count', [TransferRequestController::class, 'countPendingRequests'])->name('request.count');

    Route::get('/request/dropdown',[TransferRequestController::class, 'requestDropdown'])->name('request.dropdown');

    ########################### End Request Products #################################

    // Get auth admin branch_id
    Route::get('/auth/branch', function () {
        return response()->json(['branch_id' => auth()->user()->branch_id]);
    })->name('auth.branch');

});

Route::group(['prefix' => 'admin'] , function () {
    // admin login
    Route::get('/login', [AuthAdminController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdminController::class, 'adminLogin'])->name('admin.post.login');

});


