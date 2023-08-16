<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Models\Brand;
use App\Events\BrandCreated;
use App\Http\Requests\ListRequest;
use App\Http\Requests\BrandUpdateRequest;
use App\Http\Requests\BrandValidationRequest;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $active = $request->get('active');
        $marcas = Brand::query();
        if (isset($active)) {
            $marcas->where('active', $request->get('active'));
        } else {
            $marcas->where('active', true)
                ->orWhere('active', false);
        }
        return response()->json([
            'message' => 'success',
            'marcas' => $marcas->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     * @param  BrandValidationRequest $request
     * @return JsonResponse
     */
    public function store(BrandValidationRequest $request): JsonResponse
    {
        if (Brand::where('name', '=', $request->get('name'))->exists()) {
            return response()->json([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $marca = Brand::create($request->all());
            try {
                broadcast((new BrandCreated($marca))->broadcastToEveryone());
            } catch (\Throwable) {
            }
            return response()->json([
                'message' => 'success',
                'marca' => $marca
            ], 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $marca = Brand::find($id);
        if (empty($marca)) {
            return response()->json([
                'message' => 'Marca no encontrada'
            ], 404);
        }
        return response()->json([
            'message' => 'success',
            'marca' => $marca
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BrandUpdateRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(BrandUpdateRequest $request, int $id): JsonResponse
    {
        $marca = Brand::find($id);
        if (empty($marca)) {
            return response()->json([
                'message' => 'Marca no encontrada'
            ], 404);
        }
        $marca->update($request->all());
        try {
            broadcast((new BrandCreated($marca))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'marca' => $marca
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return int
     */
    // public function destroy($id): int
    // {
    //     return Marca::destroy($id);
    // }
    /**
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $marca = Brand::find($id);
        $marca->active = !$marca->active;
        $marca->save();
        try {
            broadcast((new BrandCreated($marca))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'marca' => $marca
        ]);
    }
}
