<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AuthAdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
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

});

Route::group(['prefix' => 'admin'] , function () {
    // admin login
    Route::get('/login', [AuthAdminController::class, 'showAdminLoginForm'])->name('admin.login');
    Route::post('/login', [AuthAdminController::class, 'adminLogin'])->name('admin.post.login');

});


