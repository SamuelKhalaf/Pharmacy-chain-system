<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthAdminController;
use App\Http\Controllers\admin\BranchController;
use App\Http\Controllers\admin\BranchInventoryController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\NotificationController;
use App\Http\Controllers\admin\PharmacyController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\ReportsController;
use App\Http\Controllers\admin\SalesController;
use App\Http\Controllers\admin\TransferProductsController;
use App\Http\Controllers\admin\TransferRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {return view('welcome');});

Route::group(['prefix' => 'dashboard' , 'middleware' => 'auth:admin'] , function () {

    Route::middleware('can:super_admin')->group(function (){
        ########################### Start Dashboard routes #################################
        Route::get('/' , [DashboardController::class, 'index'])->name('home');
        Route::get('charts/sales-count',[DashboardController::class,'getSalesCount'])->name('charts.sales-count');
        Route::get('/top-products', [DashboardController::class, 'getTopSellingProducts'])->name('dashboard.top-products');
        ########################### End Dashboard routes #################################

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

        ########################### Start Notification routes #################################
        Route::get('notification',[NotificationController::class,'index'])->name('notification.index');
        Route::get('notification/unread', [NotificationController::class, 'getUnReadNotification'])->name('notification.unread');
        Route::get('notification/{id}', [NotificationController::class, 'show'])->name('notification.show');
        Route::post('notification/{id}', [NotificationController::class, 'markAsRead'])->name('notification.markAsRead');
        ########################### End Notification routes #################################

        ########################### Start Reports routes #################################
        Route::get('reports/branches/invoices',[ReportsController::class,'getBranchInvoices'])
            ->name('reports.branches-invoices');
        Route::get('reports/branches/get-invoices',[ReportsController::class,'getInvoices'])
            ->name('reports.get-invoices');
        Route::get('reports/branches/products-count',[ReportsController::class,'getSoldProductsCount'])
            ->name('reports.products-count');
        Route::get('/reports/get-products-by-branch', [ReportsController::class, 'getProductsByBranch'])
            ->name('reports.get-products-by-branch');
        Route::get('/reports/get-sold-product-quantity', [ReportsController::class, 'getProductCount'])
            ->name('reports.get-sold-product-quantity');
        ########################### End Reports routes #################################

        // requests
        Route::get('/request',[TransferRequestController::class,'index'])->name('request.index');
        Route::post('/request/{id}/accept',[TransferRequestController::class,'acceptRequest'])->name('request.accept');
        Route::post('/request/{id}/cancel',[TransferRequestController::class,'cancelRequest'])->name('request.cancel');
        Route::get('/requests/count', [TransferRequestController::class, 'countPendingRequests'])->name('request.count');
        Route::get('/request/dropdown',[TransferRequestController::class, 'requestDropdown'])->name('request.dropdown');
    });

    Route::middleware('can:branch_admin')->group(function (){
        ########################### Start Pharmacy routes #################################
        Route::get('pharmacy',[PharmacyController::class,'index'])->name('pharmacy.index');
        Route::get('pharmacy/{branch}/{product}', [PharmacyController::class, 'show'])->name('pharmacy.show');
        Route::get('pharmacy/{branch}/{product}/edit', [PharmacyController::class, 'edit'])->name('pharmacy.edit');
        Route::put('pharmacy/{branch}/{product}', [PharmacyController::class, 'update'])->name('pharmacy.update');
        Route::delete('pharmacy/{branch}/{product}', [PharmacyController::class, 'destroy'])->name('pharmacy.destroy');
        ########################### End Pharmacy routes #################################
        // Get auth branch_admin branch_id
        Route::get('/auth/branch', function () {return response()->json(['branch_id' => auth()->user()->branch_id]);})->name('auth.branch');

        ########################### Start Request Products #################################
        Route::get('/request/make',[TransferRequestController::class,'showRequestForm'])->name('request.show-request-form');
        Route::get('/request/get-products',[TransferRequestController::class,'getSpecificBranchProducts'])->name('request.get-products');
        Route::post('/request/store',[TransferRequestController::class,'storeRequest'])->name('request.store');
        ########################### End Request Products #################################
    });
    // CRUD sales (invoices)
    Route::resource('invoice',SalesController::class)->except(['edit','update']);
    // admin logout
    Route::post('/admin/logout', [AuthAdminController::class, 'logout'])->name('admin.logout');
});

Route::group(['prefix' => 'admin', 'middleware' => 'guest:admin'], function () {
    Route::get('/login', [AuthAdminController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdminController::class, 'adminLogin'])->name('admin.post.login');
});



