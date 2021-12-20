<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{UserController, AuthController, IntegracaoEcommerceController};

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

Route::group(['middleware' => ['ipAllowed']], function () {
    Route::get('/users/store', [UserController::class, 'store']);
    Route::post('/tokens/store', [AuthController::class, 'store']);
    Route::post('/tokens/destroy', [AuthController::class, 'destroy']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    //rotas protegias pelo sanctum
});
