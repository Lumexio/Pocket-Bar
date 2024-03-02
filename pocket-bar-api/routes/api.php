<?php

//use App\Http\Controllers\ProductController;

use App\Http\Controllers\PaymentMethodsController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\TenantUserController;
use App\Http\Middleware\VerifyTenantSuscription;
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

/**
 * *This file contains the routes for the central application (not PB).
 * *This routes are used to manage the tenants, users, plans, subscriptions and payment methods.
 * *The routes for PB application are in the routes/tentant.php file.
 */

Route::post('register', [TenantUserController::class, 'store']);
Route::post('login', [TenantUserController::class, 'login']);
Route::post('logout', [TenantUserController::class, 'logout']);
Route::get('plans/', [PlanController::class, 'index']);
Route::get('plans/{id}', [PlanController::class, 'show']);
Route::post('stripe-webhook', [StripeWebhookController::class, 'handleWebhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::group(['prefix' => 'tenant'], function () {
        Route::post('/', [TenantController::class, 'store']);
        Route::post('/{id}/domain', [TenantController::class, 'addDomain']);
        Route::put('/{id}/domain/{domainId}', [TenantController::class, 'update']);
        Route::delete('/{id}', [TenantController::class, 'destroy']);
        Route::delete('/{id}/domain/{domainId}', [TenantController::class, 'deleteDomain']);
        Route::get('/{id}', [TenantController::class, 'show']);
        Route::get('/', [TenantController::class, 'index']);
    });

    Route::group(['prefix' => 'user'], function () {
        Route::get('/show', [TenantUserController::class, 'show']);
        Route::put('/update', [TenantUserController::class, 'update']);
        Route::delete('/delete', [TenantUserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'payment-method'], function () {
        Route::post('/create-setup-intent', [PaymentMethodsController::class, 'createSetupIntent']);
        Route::post('/attach-payment-method', [PaymentMethodsController::class, 'attachPaymentMethod']);
        Route::get('/', [PaymentMethodsController::class, 'index']);
        Route::post('/set-default', [PaymentMethodsController::class, 'setDefault']);
    });

    Route::middleware(VerifyTenantSuscription::class)->group(function () {
        Route::group(['prefix' => 'subscription'], function () {
            Route::post('/create-subscription', [SubscriptionController::class, 'createSubscription']);
            Route::post('/cancel-subscription', [SubscriptionController::class, 'cancelSubscription']);
        });
    });
});
