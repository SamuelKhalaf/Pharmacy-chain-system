<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\OrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::prefix('v1')->group(function () {
    // public routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    // private routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('order',[OrderController::class,'allOrders']);
        Route::post('order/store',[OrderController::class,'store']);
        Route::post('order/{id}',[OrderController::class,'show']);
        Route::post('order/accept/{id}',[OrderController::class,'acceptOrder']);
        Route::post('order/cancel/{id}',[OrderController::class,'cancelOrder']);
    });


});

