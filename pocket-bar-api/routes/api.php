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
        Route::post('/create', 'ProductController@store');
        Route::put('/update/{id}', 'ProductController@update');
        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::put('/activate/{id}', 'ProductController@activate');
    });
<<<<<<< HEAD
=======
    // Route::put("articulo/activate/{id}", "ProductController@activate");
>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events))
    Route::post('/updatephoto/{id}', 'PhotoController@updatephoto');

    Route::resource('rol', 'RolController')->except(['destroy', 'create', 'edit']);
    Route::put('/rol/activate/{id}', 'RolController@activate');
<<<<<<< HEAD

    Route::resource('marca', 'MarcaController')->except(['destroy', 'create', 'edit']);
    Route::put('/marca/activate/{id}', 'MarcaController@activate');

    Route::resource('categoria', 'CategoriaController')->except(['destroy', 'create', 'edit']);
    Route::put('/categoria/activate/{id}', 'CategoriaController@activate');
    Route::resource('mesa', 'MesaController')->except(['destroy', 'create', 'edit']);
    Route::put('/mesa/activate/{id}', 'MesaController@activate');




    Route::resource('tipo', 'TipoController')->except(['destroy', 'create', 'edit']);
    Route::put('/tipo/activate/{id}', 'TipoController@activate');

    Route::resource('proveedor', 'ProveedorController')->except(['destroy', 'create', 'edit']);
    Route::put('/proveedor/activate/{id}', 'ProveedorController@activate');
=======
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
>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events))
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
        Route::put("/cancel-product", 'TicketController@cancelProduct'); //Cancelar producto
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
        Route::post("add-money", "CajaController@addMoney"); //Agregar dinero a caja
        Route::post("remove-money", "CajaController@removeMoney"); //Quitar dinero a caja

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
