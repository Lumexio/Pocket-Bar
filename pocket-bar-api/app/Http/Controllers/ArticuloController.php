<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Models\Articulo;
use App\Events\articuloCreated;
use App\Http\Requests\ArticuleValidationRequest;
use File;
//use Illuminate\Http\File;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Support\Facades\Storage;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dat = DB::table('articulos_tbl')
            ->leftJoin('users', 'articulos_tbl.user_id', '=', 'users.id')
            ->leftJoin('categorias_tbl', 'articulos_tbl.categoria_id', '=', 'categorias_tbl.id')
            ->leftJoin('marcas_tbl', 'articulos_tbl.marca_id', '=', 'marcas_tbl.id')
            ->leftJoin('proveedores_tbl', 'articulos_tbl.proveedor_id', '=', 'proveedores_tbl.id')
            ->leftJoin('status_tbl', 'articulos_tbl.status_id', '=', 'status_tbl.id')
            ->leftJoin('tipos_tbl', 'articulos_tbl.tipo_id', '=', 'tipos_tbl.id')
            ->select('articulos_tbl.id', 'articulos_tbl.nombre_articulo', 'articulos_tbl.cantidad_articulo', 'articulos_tbl.precio_articulo', 'articulos_tbl.descripcion_articulo', 'articulos_tbl.foto_articulo', 'users.name', 'categorias_tbl.nombre_categoria', 'marcas_tbl.nombre_marca', 'proveedores_tbl.nombre_proveedor', 'status_tbl.nombre_status', 'tipos_tbl.nombre_tipo')
            ->get()
            ->map(
                function ($item) {
                    $item->foto_articulo = url("images/{$item->foto_articulo}");
                    return $item;
                }
            );

        return $dat;


        //return Articulo::all();
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * dentro hay una validacion para saber si el nombre del articulo ya exite en los registros.
     */
    public function store(ArticuleValidationRequest $request)
    {
        if (Articulo::where('nombre_articulo', '=', $request->get('nombre_articulo'))->exists()) {
            return response([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $photo = $request->file('foto_articulo');
            $articulo = $request->all();
            $articulo['user_id'] = Auth::id();
            if (isset($photo)) {
                $extension = $request->file('foto_articulo')->guessExtension();
                $name_foto =  $request->nombre_articulo . '.' . $extension;
                $request->foto_articulo->move(public_path('images'), $name_foto);
                $articulo["foto_articulo"] = $name_foto;
            }
            $articulo = Articulo::create($articulo);
            broadcast((new articuloCreated($articulo))->broadcastToEveryone());
            //articuloCreated::dispatch($articulo);
            return $articulo;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Articulo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $articulo = Articulo::find($id);
        //Obtener nombre venidero

        $newname = $request->nombre_articulo;


        //si el archivo existe
        if (file_exists(public_path("/images/$articulo->foto_articulo"))) {
            //lugar donde esta guardado el archivo existente
            $oldpath = public_path("/images/$articulo->foto_articulo");
            //nuevo nombre del archivo
            if (str_contains($articulo->foto_articulo, '.png')) {
                $newname =  $newname . '.' . "png";
            } elseif (str_contains($articulo->foto_articulo, '.jpg')) {
                $newname =  $newname . '.' . "jpg";
            }
            $newpath = public_path("/images/$newname");
            rename($oldpath, $newpath);
        }
        $articulo->update([
        'nombre_articulo' => $request->nombre_articulo,
        'cantidad_articulo' => $request->cantidad_articulo,
        'precio_articulo' => $request->precio_articulo,
        'descripcion_articulo' => $request->descripcion_articulo,
        'categoria_id' => $request->categoria_id,
        'marca_id' => $request->marca_id,
        'proveedor_id' => $request->proveedor_id,
        'status_id' => $request->status_id,
        'tipo_id' => $request->tipo_id,
        'foto_articulo' => $newname ? $newname : $articulo->foto_articulo,
        'user_id' => Auth::id()]);



        $articulo['user_id'] = Auth::id();
        broadcast((new articuloCreated($articulo))->broadcastToEveryone());
        return $articulo;
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articulo = Articulo::find($id);
        $filename = $articulo->foto_articulo;
        $path = public_path("/images/$filename");
        File::delete($path);
        $articulo = Articulo::destroy($id);
        broadcast((new articuloCreated($articulo))->broadcastToEveryone());
        return $articulo;
    }
}
