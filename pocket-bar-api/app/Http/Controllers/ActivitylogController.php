<?php

namespace App\Http\Controllers;

use App\Models\Activitylog;
use Illuminate\Support\Facades\DB;
//use App\Models\Activitylog;

use Illuminate\Http\Request;

class ActivitylogController extends Controller
{
    public function index()
    {

        $dat = DB::table("activity_log")->join('users', 'activity_log.causer_id', '=', 'users.id')->select("activity_log.description", "users.name", "activity_log.properties",  "activity_log.created_at", "activity_log.updated_at")->get();
        return response([
            "status" => 200,
            "message" => "Success",
            "payload" => $dat
        ], 200);

        //return Activitylog::all();
    }
}
