<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Events\categoriaCreated;
use App\Http\Requests\CategoriaValidationRequest;
use App\Http\Requests\ListRequest;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Categoria[]|Collection
     */
    public function index(ListRequest $request)
    {
        $categorias = Categoria::query();
        $active = $request->get('active');
        if (isset($active)) {
            $categorias->where('active', $request->get('active'));
        } else {
            $categorias->where('active', true)
                ->orWhere('active', false);
        }
        return response()->json([
            'message' => 'success',
            'categorias' => $categorias->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoriaValidationRequest $request
     * @return Response|Model
     */
    public function store(CategoriaValidationRequest $request): Response|Model
    {
        if (Categoria::where('nombre_categoria', '=', $request->get('nombre_categoria'))->exists()) {
            return response([
                'message' => ['Nombre el nombre de la categoria  ya exite.']
            ], 409);
        } else {
            $categoria = Categoria::create($request->all());
            categoriaCreated::dispatch($categoria);
            return $categoria;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Model
     */
    public function show(int $id): Model
    {
        return Categoria::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Model
     */
    public function update(Request $request, int $id)
    {
        $categoria = Categoria::find($id);
        $categoria->update($request->all());
        return $categoria;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    // public function destroy($id)
    // {
    //     // No se puede eliminar una categoria

    //     return Categoria::destroy($id);
    // }

    public function activate(int $id)
    {
        $categoria = Categoria::find($id);
        $categoria->active = !$categoria->active;
        $categoria->save();
        return $categoria;
    }
}
