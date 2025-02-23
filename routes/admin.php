<?php

use App\Http\Controllers\admin\ModeratorController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::resource('moderator', ModeratorController::class);


