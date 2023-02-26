<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Support\Facades\DB;


class ActivitylogController extends Controller
{
    public function index(): JsonResponse
    {
        $dat = DB::table("activity_log")->join('users', 'activity_log.causer_id', '=', 'users.id')->select("activity_log.description", "users.name", "activity_log.properties",  "activity_log.created_at", "activity_log.updated_at")->get();
        return response()->json(
            [
                "status" => 200,
                "message" => "Success",
                "payload" => $dat
            ]
        );
    }
}
