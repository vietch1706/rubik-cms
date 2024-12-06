<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Users\CustomersController;
use App\Http\Controllers\Users\EmployeesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('isLogin')->name('dashboard');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::prefix('/users')->middleware('isLogin')->group(function () {
        Route::prefix('/customers')->group(function () {
            Route::get('/', [CustomersController::class, 'index'])->name('customers');
            Route::get('/create', [CustomersController::class, 'create'])->name('customers.create');
            Route::post('/create', [CustomersController::class, 'store'])->name('customers.store');
            Route::get('/edit/{id}', [CustomersController::class, 'edit'])->name('customers.edit');
            Route::put('/edit/{id}', [CustomersController::class, 'update'])->name('customers.update');
            Route::delete('/delete', [CustomersController::class, 'destroy'])->name('customers.destroy');
        });
        Route::prefix('/employees')->group(function () {
            Route::get('/', [EmployeesController::class, 'index'])->name('employees');
        });
    });
    Route::prefix('/products')->group(function () {
    });
});
