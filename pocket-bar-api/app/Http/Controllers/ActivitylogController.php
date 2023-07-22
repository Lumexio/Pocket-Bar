<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class ActivitylogController extends Controller
{
    public function index(): JsonResponse
    {
        $dat = DB::table("activity_log")->join('users', 'activity_log.causer_id', '=', 'users.id')->join("branches", "users.branch_id", "=", "branches.id")
            ->select("activity_log.description", "users.name", "activity_log.subject_type", "activity_log.properties",  "activity_log.created_at", "activity_log.updated_at")->get();
        $dat = $dat->map(function ($item) {
            $item->properties = json_decode($item->properties, true);
            $subject = str_replace("App\Models\\", "", $item->subject_type);
            $item->description = match ($item->description) {
                "created" => "Creación",
                "updated" => "Actualización",
                "deleted" => "Eliminación",
                default => "Otro"
            };
            $item->subject_type = match ($subject) {
                "User" => "Usuario",
                "Product" => "Artículo",
                "Rol" => "Rol",
                "Category" => "Categoría",
                "Provider" => "Proveedor",
                "Type" => "Tipo de Artículo",
                "Ticket" => "Ticket",
                default => "Otro"
            };
            $item->description = $item->description . " de " . $item->subject_type;
            return $item;
        });
        return response()->json(
            [
                "status" => 200,
                "message" => "Success",
                "payload" => $dat
            ]
        );
    }
}
