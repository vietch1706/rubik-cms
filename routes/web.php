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

Route::prefix('admin')->name('admin.')->group(function () {
    // Public routes (no auth middleware)
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Users group
        Route::prefix('users')->name('users.')->group(function () {
            // Profile routes
            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('/', [ProfileController::class, 'edit'])->name('edit');
                Route::get('change-password', [ProfileController::class, 'changePasswordView'])->name('change_password');
                Route::put('edit', [ProfileController::class, 'update'])->name('update');
                Route::put('change-password', [ProfileController::class, 'changePassword'])->name('change_password.update');
            });

            // Resource routes for customers and employees
            Route::resource('customers', CustomersController::class)->except(['show']);
            Route::get('customers/search', [CustomersController::class, 'search'])->name('customers.search');
            Route::post('customers/{id}/show', [CustomersController::class, 'show'])->name('customers.show');

            Route::resource('employees', EmployeesController::class)->except(['show']);
            Route::get('employees/search', [EmployeesController::class, 'search'])->name('employees.search');
        });

        // Catalogs group
        Route::prefix('catalogs')->name('catalogs.')->group(function () {
            Route::resource('brands', BrandsController::class)->except(['show']);
            Route::get('brands/search', [BrandsController::class, 'search'])->name('brands.search');

            Route::resource('distributors', DistributorsController::class)->except(['show']);
            Route::get('distributors/search', [DistributorsController::class, 'search'])->name('distributors.search');

            Route::resource('categories', CategoriesController::class)->except(['show']);
            Route::get('categories/search', [CategoriesController::class, 'search'])->name('categories.search');

            Route::resource('products', ProductsController::class)->except(['show', 'destroy']);
            Route::delete('products', [ProductsController::class, 'destroy'])->name('products.destroy');
            Route::get('products/search', [ProductsController::class, 'search'])->name('products.search');
        });

        // Campaigns
        Route::resource('campaigns', CampaignsController::class)->except(['show']);
        Route::get('campaigns/search', [CampaignsController::class, 'search'])->name('campaigns.search');

        // Transactions group
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::resource('orders', OrdersController::class)->only(['index', 'create', 'store', 'destroy']);
            Route::get('orders/preview/{id}', [OrdersController::class, 'preview'])->name('orders.preview');
            Route::get('orders/search', [OrdersController::class, 'search'])->name('orders.search');

            Route::prefix('import-receipts')->name('receipts.')->group(function () {
                Route::get('/', [ImportReceiptsController::class, 'index'])->name('index');
                Route::get('preview/{id}', [ImportReceiptsController::class, 'preview'])->name('preview');
                Route::post('approve/{id}', [ImportReceiptsController::class, 'approveReceipt'])->name('approve');
                Route::delete('delete', [ImportReceiptsController::class, 'destroy'])->name('destroy');
                Route::get('search', [ImportReceiptsController::class, 'search'])->name('search');

                Route::prefix('import')->name('import.')->group(function () {
                    Route::get('/', [ImportReceiptsController::class, 'importView'])->name('index');
                    Route::post('preview', [ImportReceiptsController::class, 'previewImport'])->name('preview');
                    Route::post('process', [ImportReceiptsController::class, 'processImport'])->name('process');
                });
            });

            Route::resource('invoices', InvoicesController::class)->only(['index', 'create', 'store', 'destroy']);
            Route::get('invoices/preview/{id}', [InvoicesController::class, 'preview'])->name('invoices.preview');
            Route::get('invoices/search', [InvoicesController::class, 'search'])->name('invoices.search');
        });

        // Logs
        Route::prefix('logs')->name('logs.')->group(function () {
            Route::get('/', [LogsController::class, 'index'])->name('index');
            Route::get('preview/{id}', [LogsController::class, 'preview'])->name('preview');
            Route::delete('delete', [LogsController::class, 'destroy'])->name('destroy');
        });

        // Blogs
        Route::resource('blogs', BlogsController::class)->except(['show']);
        Route::get('blogs/search', [BlogsController::class, 'search'])->name('blogs.search');
    });
});
