<?php

namespace App\Http\Controllers;

use App\Events\TableCreated;
use App\Http\Requests\ListRequest;
use App\Http\Requests\TableUpdateRequest;
use App\Http\Requests\TableValidationRequest;
use App\Models\Table;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(ListRequest $request): JsonResponse
    {
        $mesas = Table::query();
        $mesas->where("branch_id", $request->input("branch_id", auth()->user()->branch_id));
        $active = $request->get('active');
        if (isset($active)) {
            $mesas->where('active', $request->get('active'));
        }
        return response()->json([
            'message' => 'success',
            'mesas' => $mesas->get()
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
        if (Table::where('name', '=', $request->get('name'))->where("branch_id", $request->input("branch_id", auth()->user()->branch_id))->exists()) {
            return response()->json(
                [
                    'message' => ['Nombre el nombre de la mesa  ya exite.']
                ],
                409
            );
        } else {
            $mesa = new Table();
            $mesa->name = $request->get('name');
            $mesa->description = $request->get('description');
            $mesa->branch_id = $request->input("branch_id", auth()->user()->branch_id);
            $mesa->save();

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
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return int
     */
    public function destroy(int $id): JsonResponse
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
        broadcast((new TableCreated($mesa))->broadcastToEveryone());
        return response()->json(
            [
                'message' => 'success',
                'mesa' => $mesa
            ],
            200
        );
    }
}
