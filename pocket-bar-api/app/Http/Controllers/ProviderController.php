<?php

namespace App\Http\Controllers;

use App\Events\ProviderCreated;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ListRequest;
use App\Http\Requests\ProviderUpdateRequest;
use App\Http\Requests\ProviderValidationRequest;
use App\Models\Provider;

class ProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $proveedores = Provider::query();
        $active = $request->get('active');
        if (isset($active)) {
            $proveedores->where('active', $request->get('active'));
        } else {
            $proveedores->where('active', true)
                ->orWhere('active', false);
        }
        return response()->json([
            'message' => 'success',
            'proveedores' => $proveedores->get()
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProviderValidationRequest $request
     * @return JsonResponse
     */
    public function store(ProviderValidationRequest $request): JsonResponse
    {
        if (Provider::where('name', '=', $request->get('name'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ],
                409
            );
        } else {
            $proveedor = Provider::create($request->all());
            try {
                broadcast((new ProviderCreated($proveedor))->broadcastToEveryone());
            } catch (\Throwable) {
            }
            return response()->json(
                [
                    'message' => 'success',
                    'proveedor' => $proveedor
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
        $proveedor = Provider::find($id);
        if (empty($proveedor)) {
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
        }
        return response()->json(
            [
                'message' => 'success',
                'proveedor' => $proveedor
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProviderUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProviderUpdateRequest $request, int $id): JsonResponse
    {
        $proveedor = Provider::find($id);
        if (empty($proveedor)) {
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
        }
        $proveedor->update($request->all());
        try {
            broadcast((new ProviderCreated($proveedor))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json(
            [
                'message' => 'success',
                'proveedor' => $proveedor
            ],
            200
        );
    }

    /**
     * Activate and deactivate the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $proveedor = Provider::find($id);
        if (empty($proveedor)) {
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
        }
        $proveedor->active = !$proveedor->active;
        $proveedor->save();
        broadcast((new ProviderCreated($proveedor))->broadcastToEveryone());
        return response()->json(
            [
                'message' => 'success',
                'proveedor' => $proveedor
            ],
            200
        );
    }
}
