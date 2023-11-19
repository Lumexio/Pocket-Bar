<?php

declare(strict_types=1);

use App\Http\Controllers\ActivitylogController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CashDeskController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\NominasController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkshiftController;
use App\Http\Middleware\VerifyTenantSuscription;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

#region PocketBarAPI
Route::prefix('api')->middleware(["api", InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class, VerifyTenantSuscription::class, "auth:sanctum"])->group(function () {
    Route::prefix('product')->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/activate/{id}', [ProductController::class, 'activate']);
        Route::get('/menu', [ProductController::class, 'menu']);
    });
    // Route::put("articulo/activate/{id}", "ProductController@activate");
    Route::post('/updatephoto/{id}', [PhotoController::class, 'updatephoto']);

    Route::resource('rol', RolController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/rol/activate/{id}', [RolController::class, 'activate']);
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('brand', BrandController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/brand/activate/{id}', [BrandController::class, 'activate']);
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('category', CategoryController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/category/activate/{id}', [CategoryController::class, 'activate']);
    Route::resource('table', TableController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/table/activate/{id}', [TableController::class, 'activate']);

    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('type', TypeController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/type/activate/{id}', [TypeController::class, 'activate']);
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('provider', ProviderController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/provider/activate/{id}', [ProviderController::class, 'activate']);
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('status', StatusController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/status/activate/{id}', [StatusController::class, 'activate']);
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('user', UserController::class)->except(['destroy', 'create', 'edit']);
    Route::put('/user/activate/{id}', [UserController::class, 'activate']);
    Route::resource('activitylog', ActivitylogController::class)->except(['destroy', 'update', 'store', 'create', 'edit', 'show']);

    Route::prefix('ticket')->group(function () {
        Route::get('/', [TicketController::class, 'index']); //Lista para deskstop
        Route::get('/pwa', [TicketController::class, 'indexPWA']); //Lista para pwa
        Route::post('/', [TicketController::class, 'store']); //Crear ticket
        Route::put('/tip', [TicketController::class, 'tipUpdate']); //Agregar propina
        Route::post('/pay', [TicketController::class, 'pay']); //Pagar ticket
        Route::put("/cancel-product", [TicketController::class, 'cancelProduct']); //Cancelar producto
        Route::post("/cancel", [TicketController::class, 'cancelTicket']); //Cancelar ticket
        Route::put('/add-products', [TicketController::class, 'addProducts']); //Agregar productos
    });

    Route::prefix('order')->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**
         * *listo
         */
        Route::get('/notificacion/productos', [OrdersController::class, 'index']);

        Route::put('/notificacion/productos', [TicketController::class, 'updateStatus']);
    });

    Route::prefix("cashdesk")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post("add-money", [CashDeskController::class, 'addMoney']); //Agregar dinero a caja
        Route::post("remove-money", [CashDeskController::class, 'removeMoney']); //Quitar dinero a caja

        Route::get('/mustbe', [CashDeskController::class, 'getMustBe']);
        Route::post('/close', [CashDeskController::class, 'close']); //Lo que debo de tener en caja enviando desde el front para comparar
    });

    Route::prefix("nominas")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post('/pay', [NominasController::class, 'nominasToPay']); //Pagar nominas
    });

    Route::prefix("workshift")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post('/start', [WorkshiftController::class, 'start']); //Iniciar turno
        Route::put('/close', [WorkshiftController::class, 'close']); //Cerrar turno
    });

    Route::prefix("currency")->group(function () {
        Route::get('/', [CurrencyController::class, 'index']);
        Route::post('/', [CurrencyController::class, 'store']);
        Route::get('/{id}', [CurrencyController::class, 'show']);
        Route::put('set-default/{id}', [CurrencyController::class, 'setDefault']);
    });

    Route::prefix("stock")->group(function () {
        Route::post('/add', [StockController::class, 'addStock']);
        Route::post('/remove', [StockController::class, 'removeStock']);
    });

    Route::prefix("branch")->group(function () {
        Route::get('/', [BranchController::class, 'index']);
        Route::post('/', [BranchController::class, 'store']);
        Route::get('/{id}', [BranchController::class, 'show']);
        Route::put('/{id}', [BranchController::class, 'update']);
        Route::put('/activate/{id}', [BranchController::class, 'activate']);
    });
});

Route::prefix('api')->middleware(["api", InitializeTenancyByDomain::class, PreventAccessFromCentralDomains::class, VerifyTenantSuscription::class])->group(function () {
    Route::post('login', [UserController::class, 'login']);
    Route::get('logout', [UserController::class, 'logout']);
    // test route
    Route::get('test', function () {
        return response()->json([
            'message' => 'Hello World!',
            "tenant" => tenant()
        ], 200);
    });
});

#endregion

Route::middleware(['universal'])->group(function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
});

#endregion

Route::middleware(['universal'])->group(function () {
    Route::get('/csrf-cookie', [CsrfCookieController::class, 'show'])
        ->name('sanctum.csrf-cookie');
});
