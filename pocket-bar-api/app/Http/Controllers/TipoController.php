<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Tipo;
use App\Events\tipoCreated;
use App\Http\Requests\TipoValidationRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class TipoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Tipo|array
     */
    public function index(): Collection|Tipo|array
    {
        return Tipo::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TipoValidationRequest $request
     * @return JsonResponse|Tipo|Collection
     */
    public function store(TipoValidationRequest $request):JsonResponse|Tipo|Collection
    {
        if (Tipo::where('nombre_tipo', '=', $request->get('nombre_tipo'))->exists()) {
            return response()->json(
                [
                    'message' => ['Uno de los parametros ya exite.']
                ], 409
            );
        } else {
            $tipo = Tipo::create($request->all());
            tipoCreated::dispatch($tipo);
            return $tipo;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id):Tipo
    {
        return Tipo::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Tipo
     */
    public function update(Request $request, int $id): Tipo
    {
        $tipo = Tipo::find($id);
        $tipo->update($request->all());
        return $tipo;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id): int
    {
        return Tipo::destroy($id);
    }
}
