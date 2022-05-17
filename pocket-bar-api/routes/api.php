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
        Route::delete('/delete/{id}', 'ArticuloController@destroy');
    });
    Route::post('/updatephoto/{id}', 'PhotoController@updatephoto');

    Route::resource('rol', 'RolController');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('marca', 'MarcaController');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('categoria', 'CategoriaController');
    /*Crear  si
    Eliminar no
    Mostrar un registro no */
    Route::resource('travesano', 'TravesañoController');
    Route::resource('rack', 'RackController');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('tipo', 'TipoController');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('proveedor', 'ProveedorController');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('status', 'StatusController');
    /*Crear  si
Eliminar no
Mostrar un registro no */
    Route::resource('user', 'UserController');
    Route::resource('activitylog', 'ActivitylogController');

    Route::prefix('tickets')->group(function () {
        Route::get('/list', 'TicketController@index');
        Route::get('/pwa/list', 'TicketController@indexPwa');
        Route::post('/create', 'TicketController@store');
    });

    Route::prefix('ordenes')->group(function () {
        Route::get('/notificacion/productos', 'OrdenesController@index');
        Route::put('/productos', 'OrdenesController@updateStatus');
    });
});

Route::post('login', [UserController::class, 'login']);
