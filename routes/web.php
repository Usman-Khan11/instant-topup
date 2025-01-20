<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('index');
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('logout', [AdminAuthController::class, 'logout'])->name('logout');

        // Users
        Route::get('users', [UserController::class, 'index'])->name('user');
        Route::get('user/create', [UserController::class, 'create'])->name('user.create');
        Route::get('user/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::get('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('user/store', [UserController::class, 'store'])->name('user.store');
        Route::post('user/update', [UserController::class, 'update'])->name('user.update');
    });
});
