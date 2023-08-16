<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Category;
use App\Events\CategoryCreated;
use App\Http\Requests\CategoryValidationRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\ListRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $categorias = Category::query();
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
     * @param CategoryValidationRequest $request
     * @return JsonResponse
     */
    public function store(CategoryValidationRequest $request): JsonResponse
    {
        if (Category::where('name', '=', $request->get('name'))->exists()) {
            return response()->json([
                'message' => ['Nombre el nombre de la categoria  ya exite.']
            ], 409);
        } else {
            $categoria = Category::create($request->all());
            // categoriaCreated::dispatch($categoria);
            try {
                broadcast((new CategoryCreated($categoria))->broadcastToEveryone());
            } catch (\Throwable) {
            }
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
        $categoria = Category::find($id);
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
     * @param CategoryUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CategoryUpdateRequest $request, int $id): JsonResponse
    {
        $categoria = Category::find($id);
        if (empty($categoria)) {
            return response()->json([
                'message' => 'Categoria no encontrada'
            ], 404);
        }
        $categoria->update($request->all());
        try {
            broadcast((new CategoryCreated($categoria))->broadcastToEveryone());
        } catch (\Throwable) {
        }
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
        $categoria = Category::find($id);
        if (empty($categoria)) {
            return response()->json([
                'message' => 'Categoria no encontrada'
            ], 404);
        }
        $categoria->active = !$categoria->active;
        $categoria->save();
        try {
            broadcast((new Category($categoria))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'categoria' => $categoria
        ], 200);
    }
}
