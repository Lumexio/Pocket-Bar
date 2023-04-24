<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Events\proveedorCreated;
use App\Http\Requests\ListRequest;
use App\Http\Requests\ProveedorUpdateRequest;
use App\Http\Requests\ProveedorValidationRequest;
use Illuminate\Http\Response;
use Ramsey\Collection\Collection;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $proveedores = Proveedor::query();
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
     * @param ProveedorValidationRequest $request
     * @return JsonResponse
     */
    public function store(ProveedorValidationRequest $request): JsonResponse
    {
        if (Proveedor::where('nombre_proveedor', '=', $request->get('nombre_proveedor'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ],
                409
            );
        } else {
            $proveedor = Proveedor::create($request->all());
            proveedorCreated::dispatch($proveedor);
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
        $proveedor = Proveedor::find($id);
        if (empty($proveedor))
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
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
     * @param ProveedorUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ProveedorUpdateRequest $request, int $id): JsonResponse
    {
        $proveedor = Proveedor::find($id);
        if (empty($proveedor))
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
        $proveedor->update($request->all());
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
        $proveedor = Proveedor::find($id);
        if (empty($proveedor))
            return response()->json(
                [
                    'message' => 'No se encontro el proveedor'
                ],
                404
            );
        $proveedor->active = !$proveedor->active;
        $proveedor->save();
        return response()->json(
            [
                'message' => 'success',
                'proveedor' => $proveedor
            ],
            200
        );
    }
}
