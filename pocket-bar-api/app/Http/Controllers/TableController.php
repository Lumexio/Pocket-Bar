<?php

namespace App\Http\Controllers;

<<<<<<< HEAD:pocket-bar-api/app/Http/Controllers/MesaController.php


use Illuminate\Http\JsonResponse;

=======
use App\Events\TableCreated;
use App\Http\Requests\TableUpdateRequest;
use App\Http\Requests\TableValidationRequest;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events)):pocket-bar-api/app/Http/Controllers/TableController.php

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $mesas = Table::all();
        return response()->json([
            'message' => 'success',
            'mesas' => $mesas
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  TableValidationRequest  $request
     * @return JsonResponse
     */
    public function store(TableValidationRequest $request): JsonResponse
    {
        if (Table::where('nombre_mesa', '=', $request->get('nombre_mesa'))->exists()) {
            return response()->json(
                [
                    'message' => ['Nombre el nombre de la mesa  ya exite.']
                ],
                409
            );
        } else {
<<<<<<< HEAD:pocket-bar-api/app/Http/Controllers/MesaController.php
            $mesa = Mesa::create($request->all());
=======
            $mesa = Table::create($request->all());

>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events)):pocket-bar-api/app/Http/Controllers/TableController.php
            try {
                broadcast((new TableCreated($mesa))->broadcastToEveryone());
            } catch (\Exception) {
            }
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
        $mesa = Table::find($id);
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
     * @param TableUpdateRequest $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(TableUpdateRequest $request, int $id): JsonResponse
    {
        $mesa = Table::find($id);
        if (empty($mesa)) {
            return response()->json(
                [
                    'message' => 'Mesa no encontrada'
                ],
                404
            );
        }
        $mesa->update($request->all());
        try {
            broadcast((new TableCreated($mesa))->broadcastToEveryone());
        } catch (\Throwable) {
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
<<<<<<< HEAD:pocket-bar-api/app/Http/Controllers/MesaController.php
=======
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id): JsonResponse
    {
        $mesa = Table::destroy($id);
        try {
            broadcast((new TableCreated($mesa))->broadcastToEveryone());
        } catch (\Throwable) {
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
>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events)):pocket-bar-api/app/Http/Controllers/TableController.php
     * @param int $id
     * @return JsonResponse
     */
    public function activate(int $id): JsonResponse
    {
        $mesa = Table::find($id);
        if (empty($mesa)) {
            return response()->json(
                [
                    'message' => 'Mesa no encontrada'
                ],
                404
            );
        }
        $mesa->active = !$mesa->active;
        $mesa->save();
        try {
            broadcast((new TableCreated($mesa))->broadcastToEveryone());
        } catch (\Throwable) {
        }
        return response()->json(
            [
                'message' => 'success',
                'mesa' => $mesa
            ],
            200
        );
    }
}
