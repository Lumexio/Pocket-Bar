<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Marca;

use App\Events\marcaCreated;
use App\Http\Requests\ListRequest;
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
     * @return JsonResponse|Model
     */
    public function store(MarcaValidationRequest $request): JsonResponse|Model
    {
        if (Marca::where('nombre_marca', '=', $request->get('nombre_marca'))->exists()) {
            return response()->json([
                'message' => ['Uno de los parametros ya exite.']
            ], 409);
        } else {
            $marca = Marca::create($request->all());
            marcaCreated::dispatch($marca);
            return $marca;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Model|Collection
     */
    public function show(int $id): Model|Collection
    {
        return Marca::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Model|Collection
     */
    public function update(Request $request, int $id): Model|Collection
    {
        $marca = Marca::find($id);
        $marca->update($request->all());
        return $marca;
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
     * @return Model|Collection
     */
    public function activate(int $id): Model|Collection
    {
        $marca = Marca::find($id);
        $marca->active = !$marca->active;
        $marca->save();
        return $marca;
    }
}
