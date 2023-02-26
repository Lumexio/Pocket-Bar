<?php

namespace App\Http\Controllers;

use Barryvdh\Reflection\DocBlock\Type\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use App\Events\mesaCreated;
use App\Http\Requests\MesaValidationRequest;
use App\Models\Mesa;

class MesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Collection|Mesa[]
     */
    public function index(): array|Collection
    {
        return Mesa::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  MesaValidationRequest  $request
     * @return JsonResponse|Model|Collection
     */
    public function store(MesaValidationRequest $request): JsonResponse|Model|Collection
    {
        if (Mesa::where('nombre_mesa', '=', $request->get('nombre_mesa'))->exists()) {
            return response()->json(
                [
                    'message' => ['Nombre el nombre de la mesa  ya exite.']
                ], 409
            );
        } else {
            $mesa = Mesa::create($request->all());
            mesaCreated::dispatch($mesa);
            return $mesa;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Model|Collection
     */
    public function show(int $id): Model|Collection
    {
        return Mesa::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Model|Collection
     */
    public function update(Request $request, int $id): Model|Collection
    {
        $mesa = Mesa::find($id);
        $mesa->update($request->all());
        return $mesa;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id): int
    {
        return Mesa::destroy($id);
    }
}
