<?php

//use App\Http\Controllers\ProductController;

use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantUserController;
use Illuminate\Support\Facades\Route;

//use Spatie\Activitylog\Models\Activity;

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

Route::post('register', [TenantUserController::class, 'store']);
Route::post('login', [TenantUserController::class, 'login']);
Route::post('logout', [TenantUserController::class, 'logout']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user/show', [TenantUserController::class, 'show']);
    Route::put('user/update', [TenantUserController::class, 'update']);
    Route::delete('user/delete', [TenantUserController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('tenant', [TenantController::class, 'store']);
    Route::post('tenant/{id}/domain', [TenantController::class, 'addDomain']);
    Route::put('tenant/{id}/domain/{domainId}', [TenantController::class, 'update']);
    Route::delete('tenant/{id}/', [TenantController::class, 'destroy']);
    Route::delete('tenant/{id}/domain/{domainId}', [TenantController::class, 'deleteDomain']);
    Route::get('tenant/{id}', [TenantController::class, 'show']);
    Route::get('tenant', [TenantController::class, 'index']);
});
