<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Events\categoriaCreated;
use App\Http\Requests\CategoriaUpdateRequest;
use App\Http\Requests\CategoriaValidationRequest;
use App\Http\Requests\ListRequest;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
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
     * @return JsonResponse
     */
    public function store(CategoriaValidationRequest $request): JsonResponse
    {
        if (Categoria::where('nombre_categoria', '=', $request->get('nombre_categoria'))->exists()) {
            return response()->json([
                'message' => ['Nombre el nombre de la categoria  ya exite.']
            ], 409);
        } else {
            $categoria = Categoria::create($request->all());
            // categoriaCreated::dispatch($categoria);
            broadcast((new categoriaCreated($categoria))->broadcastToEveryone());
            return response()->json([
                'message' => 'success',
                'categoria' => $categoria
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (empty($categoria)) {
            return response()->json([
                'message' => 'Categoria no encontrada'
            ], 404);
        }
        return response()->json([
            'message' => 'success',
            'categoria' => $categoria
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoriaUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoriaUpdateRequest $request, int $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (empty($categoria)) {
            return response()->json([
                'message' => 'Categoria no encontrada'
            ], 404);
        }
        $categoria->update($request->all());
        broadcast((new categoriaCreated($categoria))->broadcastToEveryone());
        return response()->json([
            'message' => 'success',
            'categoria' => $categoria
        ], 200);
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

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $categoria = Categoria::find($id);
        if (empty($categoria)) {
            return response()->json([
                'message' => 'Categoria no encontrada'
            ], 404);
        }
        $categoria->active = !$categoria->active;
        $categoria->save();
        broadcast((new categoriaCreated($categoria))->broadcastToEveryone());
        return response()->json([
            'message' => 'success',
            'categoria' => $categoria
        ], 200);
    }
}
