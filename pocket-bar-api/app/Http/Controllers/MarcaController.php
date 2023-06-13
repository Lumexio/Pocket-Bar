<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Marca;

use App\Events\marcaCreated;
use App\Http\Requests\ListRequest;
use App\Http\Requests\MarcaUpdateRequest;
use App\Http\Requests\MarcaValidationRequest;
use Illuminate\Support\Collection;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param ListRequest $request
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $active = $request->get('active');
        $marcas = Marca::query();
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
     * @param  MarcaValidationRequest $request
     * @return JsonResponse
     */
    public function store(MarcaValidationRequest $request): JsonResponse
    {
        if (Marca::where('nombre_marca', '=', $request->get('nombre_marca'))->exists()) {
            return response()->json([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $marca = Marca::create($request->all());
            broadcast((new marcaCreated($marca))->broadcastToEveryone());
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
        $marca = Marca::find($id);
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
     * @param  MarcaUpdateRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(MarcaUpdateRequest $request, int $id): JsonResponse
    {
        $marca = Marca::find($id);
        if (empty($marca)) {
            return response()->json([
                'message' => 'Marca no encontrada'
            ], 404);
        }
        $marca->update($request->all());
        broadcast((new marcaCreated($marca))->broadcastToEveryone());
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
        $marca = Marca::find($id);
        $marca->active = !$marca->active;
        $marca->save();
        broadcast((new marcaCreated($marca))->broadcastToEveryone());
        return response()->json([
            'message' => 'success',
            'marca' => $marca
        ]);
    }
}
