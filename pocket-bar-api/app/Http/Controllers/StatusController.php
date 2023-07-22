<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\Status;
use App\Events\statusCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class StatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $status = Status::all();
        return \response()->json([
            "message" => "success",
            "data" => $status
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
        $status = Status::create($request->all());
        try {
            statusCreated::dispatch($status);
        } catch (\Exception) {
        }
        return \response()->json([
            "message" => "success",
            "data" => $status
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
        $status = Status::find($id);
        if (!empty($status)) {
            return \response()->json([
                "message" => "success",
                "data" => $status
            ]);
        } else {
            return \response()->json([
                "message" => "Status no encontrado",
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
        $status = Status::find($id);
        if (!empty($status)) {
            $status->update($request->all());
            try {
                statusCreated::dispatch($status);
            } catch (\Exception) {
            }
            return \response()->json([
                "message" => "success",
                "data" => $status
            ]);
        } else {
            return \response()->json([
                "message" => "Status no encontrado",
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    // public function destroy($id)
    // {
    //     return Status::destroy($id);
    // }
}
