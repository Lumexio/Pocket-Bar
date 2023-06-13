<?php

namespace App\Http\Controllers;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Events\mesaCreated;
use App\Http\Requests\MesaUpdateRequest;
use App\Http\Requests\MesaValidationRequest;
use App\Models\Mesa;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $mesas = Mesa::all();
        return response()->json([
            'message' => 'success',
            'mesas' => $mesas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MesaValidationRequest  $request
     * @return JsonResponse
     */
    public function store(MesaValidationRequest $request): JsonResponse
    {
        if (Mesa::where('nombre_mesa', '=', $request->get('nombre_mesa'))->exists()) {
            return response()->json(
                [
                    'message' => ['Nombre el nombre de la mesa  ya exite.']
                ],
                409
            );
        } else {
            $mesa = Mesa::create($request->all());

            broadcast((new mesaCreated($mesa))->broadcastToEveryone());
            return response()->json(
                [
                    'message' => 'success',
                    'mesa' => $mesa
                ],
                201
            );
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
        $mesa = Mesa::find($id);
        if (empty($mesa)) {
            return response()->json(
                [
                    'message' => 'Mesa no encontrada'
                ],
                404
            );
        }
        return response()->json(
            [
                'message' => 'success',
                'mesa' => $mesa
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param MesaUpdateRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(MesaUpdateRequest $request, int $id): JsonResponse
    {
        $mesa = Mesa::find($id);
        if (empty($mesa)) {
            return response()->json(
                [
                    'message' => 'Mesa no encontrada'
                ],
                404
            );
        }
        $mesa->update($request->all());
        broadcast((new mesaCreated($mesa))->broadcastToEveryone());
        return response()->json(
            [
                'message' => 'success',
                'mesa' => $mesa
            ],
            200
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id): JsonResponse
    {
        $mesa = Mesa::destroy($id);
        broadcast((new mesaCreated($mesa))->broadcastToEveryone());
        return response()->json(
            [
                'message' => 'success',
                'mesa' => $mesa
            ],
            200
        );
    }
}
