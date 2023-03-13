<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Articulo;
use App\Events\articuloCreated;
use App\Http\Requests\ArticuleValidationRequest;
use App\Http\Requests\ListRequest;
use File;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $showActive = $request->get('showActive');
        $dat = DB::table('articulos_tbl as art')
            ->leftJoin('users', 'art.user_id', '=', 'users.id')
            ->leftJoin('categorias_tbl as cat', 'art.categoria_id', '=', 'cat.id')
            ->leftJoin('marcas_tbl', 'art.marca_id', '=', 'marcas_tbl.id')
            ->leftJoin('proveedores_tbl as prov', 'art.proveedor_id', '=', 'prov.id')
            ->leftJoin('status_tbl', 'art.status_id', '=', 'status_tbl.id')
            ->leftJoin('tipos_tbl', 'art.tipo_id', '=', 'tipos_tbl.id');
        if (isset($showActive)) {
            $dat = $showActive ? $dat->whereNull('art.deactivated_at') : $dat->whereNotNull('art.deactivated_at');
        }
        $dat = $dat->select('art.id', 'art.nombre_articulo', 'art.cantidad_articulo', 'art.precio_articulo',  'art.descripcion_articulo', 'art.foto_articulo', 'users.name', 'cat.nombre_categoria', 'marcas_tbl.nombre_marca', 'prov.nombre_proveedor', 'status_tbl.nombre_status', 'tipos_tbl.nombre_tipo', "art.deactivated_at")
            ->get()
            ->map(
                function ($item) {
                    $item->foto_articulo = url("images/$item->foto_articulo");
                    return $item;
                }
            );
        return response()->json(["message" => "success", "articulos" => $dat]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param ArticuleValidationRequest $request
     * @return JsonResponse
     *
     * dentro hay una validacion para saber si el nombre del articulo ya exite en los registros.
     */
    public function store(ArticuleValidationRequest $request): JsonResponse
    {
        if (Articulo::where('nombre_articulo', '=', $request->get('nombre_articulo'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ], 409
            );
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
            return response()->json(["message" => "success", "articulo" => $articulo], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Collection|Model|null
     */
    public function show(int $id): Collection|Model|null
    {
        return Articulo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $article = Articulo::find($id);

        if(isset($request->foto_articulo)){
            $newName = $request->nombre_articulo;
            $filename = $article->foto_articulo;
            //lugar donde esta guardado el archivo existente
            $oldPath = public_path("/images/$filename");
            $filename =  $newName . '.' . "jpg";
            $newPath = public_path("/images/$filename");
            rename($oldPath, $newPath);
            $article["foto_articulo"] = $filename;
        }
        $article->update($request->all());
        $article['user_id'] = Auth::id();
        broadcast((new articuloCreated())->broadcastToEveryone());
        return response()->json(["message" => "success", "articulo" => $article], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $articulo = Articulo::find($id);
        if (isset($articulo->deactivated_at)) {
            $articulo->deactivated_at = null;
            $message = "Articulo Activado";
        } else {
            $filename = $articulo->foto_articulo;
            if (isset($filename)) {
                $path = public_path("/images/$filename");
                File::delete($path);
            }
            $articulo->deactivated_at = now();
            $message = "Articulo Desactivado";
        }
        $articulo->save();
        broadcast((new articuloCreated($articulo))->broadcastToEveryone());
        return response()->json(['message' => $message, 'articulo' => $articulo]);
    }
}
