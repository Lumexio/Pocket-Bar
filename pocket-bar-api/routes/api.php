<?php

//use App\Http\Controllers\ArticuloController;
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
    Route::prefix('articulo')->group(function () {
        Route::post('/create', 'ArticuloController@store');
        Route::put('/update/{id}', 'ArticuloController@update');
        Route::get('/list', 'ArticuloController@index');
        Route::put('/activate/{id}', 'ArticuloController@activate');
    });
    // Route::put("articulo/activate/{id}", "ArticuloController@activate");
    Route::post('/updatephoto/{id}', 'PhotoController@updatephoto');

    Route::resource('rol', 'RolController')->except(['destroy', 'create', 'edit']);
    Route::put('/rol/activate/{id}', 'RolController@activate');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('marca', 'MarcaController')->except(['destroy', 'create', 'edit']);
    Route::put('/marca/activate/{id}', 'MarcaController@activate');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('categoria', 'CategoriaController')->except(['destroy', 'create', 'edit']);
    Route::put('/categoria/activate/{id}', 'CategoriaController@activate');
    Route::resource('mesa', 'MesaController')->except(['destroy', 'create', 'edit']);


    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('tipo', 'TipoController')->except(['destroy', 'create', 'edit']);
    Route::put('/tipo/activate/{id}', 'TipoController@activate');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('proveedor', 'ProveedorController')->except(['destroy', 'create', 'edit']);
    Route::put('/proveedor/activate/{id}', 'ProveedorController@activate');
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

    Route::prefix('tickets')->group(function () {
        Route::get('/list', 'TicketController@index'); //Lista para deskstop
        Route::get('/pwa/list', 'TicketController@indexPwa'); //Lista para pantallas moviles
        Route::post('/create', 'TicketController@store'); //Crear ticket
        Route::put('/tip', 'TicketController@tipUpdate'); //Crear ticket
        Route::post('/pay', 'TicketController@pay'); //pagar cuenta

        Route::post("/cancel", 'TicketController@cancelTicket');
        /**Añadir productos a un ticket existe
         * !Probar
         * *Francisco
         */
        Route::put('/add/products', 'TicketController@addProducts');
    });

    Route::prefix('ordenes')->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**
         * *listo
         */
        Route::get('/notificacion/productos', 'OrdenesController@index');

        Route::put('/notificacion/productos', 'TicketController@updateStatus');
    });

    Route::prefix("caja")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**Lo que debo de tener en caja
         * *Listo
         */
        Route::get('/mustbe', 'CajaController@getMustBe');
        /**Lo que debo de tener en caja
         * !Por probar
         * *Francisco
         */
        Route::post('/close', 'CajaController@close'); //Lo que debo de tener en caja enviando desde el front para comparar
    });

    Route::prefix("nominas")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**Lo que debo de tener en caja
         * !Por probar
         * *Francisco
         */
        Route::post('/pay', 'NominasController@nominasToPay');
    });

    Route::prefix("workshift")->middleware(['auth:sanctum', \Fruitcake\Cors\HandleCors::class])->group(function () {
        /**Lo que debo de tener en caja
         * !Por probar
         * *Francisco
         */
        Route::post('/start', 'WorkshiftController@start');
        Route::put('/close', 'WorkshiftController@close');
    });
});

Route::post('login', [UserController::class, 'login']);
Route::get('logout', [UserController::class, 'logout']);
