<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Events\rolCreated;
use Illuminate\Http\JsonResponse;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $roles = Rol::select("id", "name as name_rol", "active")->get();
        return \response()->json([
            "message" => "success",
            "data" => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $rol = Rol::create($request->all());
        try {
            broadcast((new rolCreated($rol))->broadcastToEveryone());
        } catch (\Exception) {
        }
        return \response()->json([
            "message" => "success",
            "data" => $rol
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $rol = Rol::find($id);
        if (!empty($rol)) {
            return \response()->json([
                "message" => "success",
                "data" => $rol
            ]);
        } else {
            return \response()->json([
                "message" => "Rol no encontrado",
                "data" => null
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $rol = Rol::find($id);
        if (empty($rol)) {
            return \response()->json([
                "message" => "Rol no encontrado",
                "data" => null
            ], 404);
        }
        $rol->update($request->all());
        return \response()->json([
            "message" => "success",
            "data" => $rol
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return int
     */
    // public function destroy($id)
    // {
    //     return Rol::destroy($id);
    // }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function activate($id): JsonResponse
    {
        $rol = Rol::find($id);
        if (empty($rol)) {
            return \response()->json([
                "message" => "Rol no encontrado",
                "data" => null
            ], 404);
        }
        $rol->update(['activo' => !$rol->activo]);
        return \response()->json([
            "message" => "success",
            "data" => $rol
        ]);
    }
}
