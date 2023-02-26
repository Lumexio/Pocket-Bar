<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Events\proveedorCreated;
use App\Http\Requests\ListRequest;
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
     * @return JsonResponse|Proveedor|Model|Collection
     */
    public function store(ProveedorValidationRequest $request): JsonResponse|Proveedor|Model|Collection
    {
        if (Proveedor::where('nombre_proveedor', '=', $request->get('nombre_proveedor'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ], 409
            );
        } else {
            $proveedor = Proveedor::create($request->all());
            proveedorCreated::dispatch($proveedor);
            return $proveedor;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Proveedor|Model|Collection
     */
    public function show(int $id): Proveedor|Model|Collection
    {
        return Proveedor::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Proveedor|Model|Collection
     */
    public function update(Request $request, int $id): Proveedor|Model|Collection
    {
        $proveedor = Proveedor::find($id);
        $proveedor->update($request->all());
        return $proveedor;
    }

    /**
     * Activate and deactivate the specified resource from storage.
     *
     * @param int $id
     * @return Model|Collection|Proveedor
     */
    public function activate(int $id): Model|Collection|Proveedor
    {
        $proveedor = Proveedor::find($id);
        $proveedor->active = !$proveedor->active;
        $proveedor->save();
        return $proveedor;
    }
}
