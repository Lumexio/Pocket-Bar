<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proveedor;
use App\Events\proveedorCreated;
use App\Http\Requests\ListRequest;
use App\Http\Requests\ProveedorValidationRequest;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListRequest $request)
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProveedorValidationRequest $request)
    {
        if (Proveedor::where('nombre_proveedor', '=', $request->get('nombre_proveedor'))->exists()) {
            return response([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $proveedor = Proveedor::create($request->all());
            proveedorCreated::dispatch($proveedor);
            return $proveedor;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Proveedor::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->update($request->all());
        return $proveedor;
    }

    /**
     * Activate and deactivate the specified resource from storage.
     *
     * @param  int  $id
     * @return Model
     */
    public function activate($id)
    {
        $proveedor = Proveedor::find($id);
        $proveedor->active = !$proveedor->active;
        $proveedor->save();
        return $proveedor;
    }
}
