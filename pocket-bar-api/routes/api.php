<?php

//use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\UserController;
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

Route::post('login', [UserController::class, 'login']);




/*Crear  si
Eliminar no
Mostrar un registro no */
Route::resource('user', 'UserController');


/*Crear  si
Eliminar no
Mostrar un registro no */
Route::resource('articulo', 'ArticuloController');



/*Crear  si
Eliminar no
Mostrar un registro no */
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
