<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Blogs\BlogsController;
use App\Http\Controllers\Campaigns\CampaignsController;
use App\Http\Controllers\Catalogs\BrandsController;
use App\Http\Controllers\Catalogs\CategoriesController;
use App\Http\Controllers\Catalogs\DistributorsController;
use App\Http\Controllers\Catalogs\ProductsController;
use App\Http\Controllers\Logs\LogsController;
use App\Http\Controllers\Transactions\ImportReceiptsController;
use App\Http\Controllers\Transactions\InvoicesController;
use App\Http\Controllers\Transactions\OrdersController;
use App\Http\Controllers\Users\CustomersController;
use App\Http\Controllers\Users\EmployeesController;
use App\Http\Controllers\Users\ProfileController;
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
    Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // NOTE: web group of contain users information
    Route::group([
        'prefix' => 'users',
        'middleware' => ['auth'],
    ], function () {
        Route::group([
            'prefix' => 'profile',
        ], function () {
            Route::get('/', [ProfileController::class, 'edit'])->name('profile');
            Route::get('/change-password', [ProfileController::class, 'changePasswordView'])->name('profile.changePassword');
            Route::put('/edit', [ProfileController::class, 'update'])->name('profile.update');
            Route::put('/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword.update');
        });
        Route::group([
            'prefix' => 'customers',
        ], function () {
            Route::get('/', [CustomersController::class, 'index'])->name('customers');
            Route::get('/create', [CustomersController::class, 'create'])->name('customers.create');
            Route::post('/create', [CustomersController::class, 'store'])->name('customers.store');
            Route::get('/edit/{id}', [CustomersController::class, 'edit'])->name('customers.edit');
            Route::put('/edit/{id}', [CustomersController::class, 'update'])->name('customers.update');
            Route::delete('/delete', [CustomersController::class, 'destroy'])->name('customers.destroy');
            Route::get('/search', [CustomersController::class, 'search'])->name('customers.search');
            Route::post('/show/{id}', [CustomersController::class, 'show'])->name('customers.show');
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
            Route::get('/search', [EmployeesController::class, 'search'])->name('employees.search');
        });
    });
    Route::group([
        'prefix' => 'catalogs',
        'middleware' => ['auth'],
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
            Route::get('/search', [BrandsController::class, 'search'])->name('brands.search');
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
            Route::get('/search', [DistributorsController::class, 'search'])->name('distributors.search');
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
            Route::get('/search', [CategoriesController::class, 'search'])->name('categories.search');
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
            Route::get('/search', [ProductsController::class, 'search'])->name('products.search');
        });
    });
    Route::group([
        'prefix' => 'campaigns',
        'middleware' => ['auth'],
    ], function () {
        Route::group([
            'prefix' => 'campaigns'
        ], function () {
            Route::get('/', [CampaignsController::class, 'index'])->name('campaigns');
            Route::get('/create', [CampaignsController::class, 'create'])->name('campaigns.create');
            Route::post('/create', [CampaignsController::class, 'store'])->name('campaigns.store');
            Route::get('/edit/{id}', [CampaignsController::class, 'edit'])->name('campaigns.edit');
            Route::put('/edit/{id}', [CampaignsController::class, 'update'])->name('campaigns.update');
            Route::delete('/delete', [CampaignsController::class, 'destroy'])->name('campaigns.destroy');
            Route::get('/search', [CampaignsController::class, 'search'])->name('campaigns.search');
        });
    });
    Route::group([
        'prefix' => 'transactions',
        'middleware' => ['auth'],
    ], function () {
        Route::group([
            'prefix' => 'orders'
        ], function () {
            Route::get('/', [OrdersController::class, 'index'])->name('orders');
            Route::get('/create', [OrdersController::class, 'create'])->name('orders.create');
            Route::post('/create', [OrdersController::class, 'store'])->name('orders.store');
            Route::get('/preview/{id}', [OrdersController::class, 'preview'])->name('orders.preview');
            Route::delete('/delete', [OrdersController::class, 'destroy'])->name('orders.destroy');
            Route::get('/search', [OrdersController::class, 'search'])->name('orders.search');
        });
        Route::group([
            'prefix' => 'import-receipts'
        ], function () {
            Route::get('/', [ImportReceiptsController::class, 'index'])->name('receipts');
            Route::get('/preview/{id}', [ImportReceiptsController::class, 'preview'])->name('receipts.preview');
            Route::post('/approve/{id}', [ImportReceiptsController::class, 'approveReceipt'])->name('receipts.approve');
            Route::group([
                'prefix' => 'import'
            ], function () {
                Route::get('/', [ImportReceiptsController::class, 'importView'])->name('import');
                Route::post('/preview', [ImportReceiptsController::class, 'previewImport'])->name('import.preview');
                Route::post('/process', [ImportReceiptsController::class, 'processImport'])->name('import.process');
            });
            Route::delete('/delete', [ImportReceiptsController::class, 'destroy'])->name('receipts.destroy');
            Route::get('/search', [ImportReceiptsController::class, 'search'])->name('receipts.search');
        });
        Route::group([
            'prefix' => 'invoices'
        ], function () {
            Route::get('/', [InvoicesController::class, 'index'])->name('invoices');
            Route::get('/create', [InvoicesController::class, 'create'])->name('invoices.create');
            Route::post('/create', [InvoicesController::class, 'store'])->name('invoices.store');
            Route::get('/preview/{id}', [InvoicesController::class, 'preview'])->name('invoices.preview');
            Route::delete('/delete', [InvoicesController::class, 'destroy'])->name('invoices.destroy');
            Route::get('/search', [InvoicesController::class, 'search'])->name('invoices.search');
        });
    });
    Route::group([
        'prefix' => 'logs',
        'middleware' => ['auth'],
    ], function () {
        Route::get('/logs', [LogsController::class, 'index'])->name('logs');
        Route::get('/logs/preview/{id}', [LogsController::class, 'preview'])->name('logs.preview');
        Route::delete('/logs/delete', [LogsController::class, 'destroy'])->name('logs.destroy');
    });
    Route::group([
        'prefix' => 'blogs',
        'middleware' => ['auth'],
    ], function () {
        Route::group([
            'prefix' => 'blogs',
        ], function () {
            Route::get('/', [BlogsController::class, 'index'])->name('blogs');
            Route::get('/create', [BlogsController::class, 'create'])->name('blogs.create');
            Route::post('/create', [BlogsController::class, 'store'])->name('blogs.store');
            Route::get('/edit/{id}', [BlogsController::class, 'edit'])->name('blogs.edit');
            Route::put('/edit/{id}', [BlogsController::class, 'update'])->name('blogs.update');
            Route::delete('/delete', [BlogsController::class, 'destroy'])->name('blogs.destroy');
            Route::get('/search', [BlogsController::class, 'search'])->name('blogs.search');
        });
    });
});
