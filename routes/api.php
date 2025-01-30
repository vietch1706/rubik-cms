<?php

use App\Http\Controllers\Api\Catalogs\ProductsController;
use App\Http\Controllers\Api\Users\Get;
use App\Http\Controllers\Api\Users\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group([
    'prefix' => 'v1',
], function () {
    Route::get('/products/get', [ProductsController::class, 'show'])->name('api.products.get');
    Route::get('customers/get', Get::class)->name('api.customers.get');
    Route::post('login', Login::class)->name('api.login');

});
