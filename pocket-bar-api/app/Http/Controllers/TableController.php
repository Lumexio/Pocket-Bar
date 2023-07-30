<?php

namespace App\Http\Controllers;






use App\Events\TableCreated;
use App\Http\Requests\TableUpdateRequest;
use App\Http\Requests\TableValidationRequest;
use App\Models\Table;
use Illuminate\Http\JsonResponse;

use Request;


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
        if (Table::where('nombre_mesa', '=', $request->get('nombre_mesa'))->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->exists()) {
            return response()->json(
                [
                    'message' => ['Nombre el nombre de la mesa  ya exite.']
                ],
                409
            );
        } else {
            $mesa = Table::create($request->all());

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
    public function show(Request $request, int $id): JsonResponse
    {
        $mesa = Table::where('id', $id)->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->first();
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
        $mesa = Table::where('id', $id)->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->first();
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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $mesa = Table::where('id', $id)->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->first();
        $mesa->delete();
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
     * @param int $id
     * @return JsonResponse
     */
    public function activate(Request $request, int $id): JsonResponse
    {
        $mesa = Table::where('id', $id)->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->first();
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
