<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Catalogs\BrandsController;
use App\Http\Controllers\Catalogs\CategoriesController;
use App\Http\Controllers\Catalogs\DistributorsController;
use App\Http\Controllers\Catalogs\ProductsController;
use App\Http\Controllers\Transactions\ImportReceiptsController;
use App\Http\Controllers\Transactions\OrdersController;
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

Route::group([
    'prefix' => 'admin',
], function () {
    Route::get('/', [DashboardController::class, 'index'])->middleware('isLogin')->name('dashboard');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // NOTE: web group of contain users information
    Route::group([
        'prefix' => 'users',
        'middleware' => ['isLogin'],
    ], function () {
        Route::group([
            'prefix' => 'customers',
        ], function () {
            Route::get('/', [CustomersController::class, 'index'])->name('customers');
            Route::get('/create', [CustomersController::class, 'create'])->name('customers.create');
            Route::post('/create', [CustomersController::class, 'store'])->name('customers.store');
            Route::get('/edit/{id}', [CustomersController::class, 'edit'])->name('customers.edit');
            Route::put('/edit/{id}', [CustomersController::class, 'update'])->name('customers.update');
            Route::delete('/delete', [CustomersController::class, 'destroy'])->name('customers.destroy');
        });
        Route::group([
            'prefix' => 'employees',
        ], function () {
            Route::get('/', [EmployeesController::class, 'index'])->name('employees');
            Route::get('/create', [EmployeesController::class, 'create'])->name('employees.create');
            Route::post('/create', [EmployeesController::class, 'store'])->name('employees.store');
            Route::get('/edit/{id}', [EmployeesController::class, 'edit'])->name('employees.edit');
            Route::put('/edit/{id}', [EmployeesController::class, 'update'])->name('employees.update');
            Route::delete('/delete', [EmployeesController::class, 'destroy'])->name('employees.destroy');
        });
    });
    Route::group([
        'prefix' => 'catalogs',
        'middleware' => ['isLogin'],
    ], function () {
        Route::group([
            'prefix' => 'brands'
        ], function () {
            Route::get('/', [BrandsController::class, 'index'])->name('brands');
            Route::get('/create', [BrandsController::class, 'create'])->name('brands.create');
            Route::post('/create', [BrandsController::class, 'store'])->name('brands.store');
            Route::get('/edit/{id}', [BrandsController::class, 'edit'])->name('brands.edit');
            Route::put('/edit/{id}', [BrandsController::class, 'update'])->name('brands.update');
            Route::delete('/delete', [BrandsController::class, 'destroy'])->name('brands.destroy');
        });
        Route::group([
            'prefix' => 'distributors'
        ], function () {
            Route::get('/', [DistributorsController::class, 'index'])->name('distributors');
            Route::get('/create', [DistributorsController::class, 'create'])->name('distributors.create');
            Route::post('/create', [DistributorsController::class, 'store'])->name('distributors.store');
            Route::get('/edit/{id}', [DistributorsController::class, 'edit'])->name('distributors.edit');
            Route::put('/edit/{id}', [DistributorsController::class, 'update'])->name('distributors.update');
            Route::delete('/delete', [DistributorsController::class, 'destroy'])->name('distributors.destroy');
        });
        Route::group([
            'prefix' => 'categories'
        ], function () {
            Route::get('/', [CategoriesController::class, 'index'])->name('categories');
            Route::get('/create', [CategoriesController::class, 'create'])->name('categories.create');
            Route::post('/create', [CategoriesController::class, 'store'])->name('categories.store');
            Route::get('/edit/{id}', [CategoriesController::class, 'edit'])->name('categories.edit');
            Route::put('/edit/{id}', [CategoriesController::class, 'update'])->name('categories.update');
            Route::delete('/delete', [CategoriesController::class, 'destroy'])->name('categories.destroy');
        });
        Route::group([
            'prefix' => 'products'
        ], function () {
            Route::get('/', [ProductsController::class, 'index'])->name('products');
            Route::get('/create', [ProductsController::class, 'create'])->name('products.create');
            Route::post('/create', [ProductsController::class, 'store'])->name('products.store');
            Route::get('/edit/{id}', [ProductsController::class, 'edit'])->name('products.edit');
            Route::put('/edit/{id}', [ProductsController::class, 'update'])->name('products.update');
            Route::delete('/delete', [ProductsController::class, 'destroy'])->name('products.destroy');
        });
    });
    Route::group([
        'prefix' => 'transactions',
        'middleware' => ['isLogin'],
    ], function () {
        Route::group([
            'prefix' => 'orders'
        ], function () {
            Route::get('/', [OrdersController::class, 'index'])->name('orders');
            Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
            Route::post('/create', [OrdersController::class, 'store'])->name('orders.store');
            Route::get('/preview/{id}', [OrdersController::class, 'edit'])->name('orders.preview');
            Route::delete('/delete', [OrdersController::class, 'destroy'])->name('orders.destroy');
        });
        Route::group([
            'prefix' => 'import-receipts'
        ], function () {
            Route::get('/', [ImportReceiptsController::class, 'index'])->name('receipts');
            Route::get('/preview/{id}', [ImportReceiptsController::class, 'edit'])->name('receipts.preview');
            Route::get('/import', [ImportReceiptsController::class, 'importView'])->name('receipts.import');
//            Route::post('/import', 'MyController@import')->name('import');
            Route::delete('/delete', [ImportReceiptsController::class, 'destroy'])->name('receipts.destroy');
        });
    });
});
