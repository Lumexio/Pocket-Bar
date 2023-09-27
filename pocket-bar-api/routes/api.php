<?php

//use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

Route::middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::prefix('product')->group(function () {
        Route::post('/', 'ProductController@store');
        Route::put('/{id}', 'ProductController@update');
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::put('/activate/{id}', 'ProductController@activate');
        Route::get('/menu', 'ProductController@menu');
    });
    // Route::put("articulo/activate/{id}", "ProductController@activate");
    Route::post('/updatephoto/{id}', 'PhotoController@updatephoto');

    Route::resource('rol', 'RolController')->except(['destroy', 'create', 'edit']);
    Route::put('/rol/activate/{id}', 'RolController@activate');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('brand', 'BrandController')->except(['destroy', 'create', 'edit']);
    Route::put('/brand/activate/{id}', 'BrandController@activate');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('category', 'CategoryController')->except(['destroy', 'create', 'edit']);
    Route::put('/category/activate/{id}', 'CategoryController@activate');
    Route::resource('table', 'TableController')->except(['destroy', 'create', 'edit']);
    Route::put('/table/activate/{id}', 'TableController@activate');

    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('type', 'TypeController')->except(['destroy', 'create', 'edit']);
    Route::put('/type/activate/{id}', 'TypeController@activate');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('provider', 'ProviderController')->except(['destroy', 'create', 'edit']);
    Route::put('/provider/activate/{id}', 'ProviderController@activate');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('status', 'StatusController')->except(['destroy', 'create', 'edit']);
    Route::put('/status/activate/{id}', 'StatusController@activate');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('user', 'UserController')->except(['destroy', 'create', 'edit']);
    Route::put('/user/activate/{id}', 'UserController@activate');
    Route::resource('activitylog', 'ActivitylogController')->except(['destroy', 'update', 'store', 'create', 'edit', 'show']);

    Route::prefix('ticket')->group(function () {
        Route::get('/', 'TicketController@index'); //Lista para deskstop
        Route::get('/pwa', 'TicketController@indexPwa'); //Lista para pantallas moviles
        Route::post('/', 'TicketController@store'); //Crear ticket
        Route::put('/tip', 'TicketController@tipUpdate'); //Crear ticket
        Route::post('/pay', 'TicketController@pay'); //pagar cuenta
        Route::put("/cancel-product", 'TicketController@cancelProduct'); //Cancelar producto
        Route::post("/cancel", 'TicketController@cancelTicket');
        Route::put('/add-products', 'TicketController@addProducts');
    });

    Route::prefix('order')->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**
         * *listo
         */
        Route::get('/notificacion/productos', 'OrdersController@index');

        Route::put('/notificacion/productos', 'TicketController@updateStatus');
    });

    Route::prefix("cashdesk")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post("add-money", "CashDeskController@addMoney"); //Agregar dinero a caja
        Route::post("remove-money", "CashDeskController@removeMoney"); //Quitar dinero a caja

        Route::get('/mustbe', 'CashDeskController@getMustBe');
        Route::post('/close', 'CashDeskController@close'); //Lo que debo de tener en caja enviando desde el front para comparar
    });

    Route::prefix("nominas")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post('/pay', 'NominasController@nominasToPay');
    });

    Route::prefix("workshift")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        Route::post('/start', 'WorkshiftController@start');
        Route::put('/close', 'WorkshiftController@close');
    });

    Route::prefix("currency")->group(function () {
        Route::get('/', 'CurrencyController@index');
        Route::post('/', 'CurrencyController@store');
        Route::get('/{id}', 'CurrencyController@show');
        Route::put('set-default/{id}', 'CurrencyController@setDefault');
    });

    Route::prefix("stock")->group(function () {
        Route::post('/add', 'StockController@addStock');
        Route::post('/remove', 'StockController@removeStock');
    });

    Route::prefix("branch")->group(function () {
        Route::get('/', 'BranchController@index');
        Route::post('/', 'BranchController@store');
        Route::get('/{id}', 'BranchController@show');
        Route::put('/{id}', 'BranchController@update');
        Route::put('/activate/{id}', 'BranchController@activate');
    });
});

Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout']);
