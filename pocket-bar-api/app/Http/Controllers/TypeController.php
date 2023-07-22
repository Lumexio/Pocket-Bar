<?php

namespace App\Http\Controllers;

use App\Events\TypeCreated;
use App\Http\Requests\TypeValidationRequest;
use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $tipos = Type::all();
        return response()->json([
            'message' => 'success',
            'tipos' => $tipos
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TypeValidationRequest $request
     * @return JsonResponse
     */
    public function store(TypeValidationRequest $request): JsonResponse
    {
        if (Type::where('nombre_tipo', '=', $request->get('nombre_tipo'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ],
                409
            );
        } else {
            $tipo = Type::create($request->all());
            try {
                TypeCreated::dispatch($tipo);
                broadcast((new TypeCreated($tipo))->broadcastToEveryone());
            } catch (\Throwable) {
            }
            return response()->json([
                'message' => 'success',
                'tipo' => $tipo
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
        $tipo = Type::find($id);
        if (empty($tipo)) {
            return response()->json(
                [
                    'message' => ['El tipo no existe.']
                ],
                404
            );
        }
        return response()->json([
            'message' => 'success',
            'tipo' => $tipo
        ], 200);
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
        $tipo = Type::find($id);
        if (empty($tipo)) {
            return response()->json(
                [
                    'message' => ['El tipo no existe.']
                ],
                404
            );
        }
        $tipo->update($request->all());
        try {
            broadcast((new TypeCreated($tipo))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'tipo' => $tipo
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $tipo = Type::destroy($id);
        try {
            broadcast((new TypeCreated($tipo))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json([
            'message' => 'success',
            'tipo' => $tipo
        ], 200);
    }

    /**
     * Activate and deactivate the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $tipo = Type::find($id);
        if (empty($tipo)) {
            return response()->json(
                [
                    'message' => 'No se encontro el tipo'
                ],
                404
            );
        }
        $tipo->active = !$tipo->active;
        $tipo->save();
        try {
            broadcast((new TypeCreated($tipo))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json(
            [
                'message' => 'success',
                'tipo' => $tipo
            ],
            200
        );
    }
}
