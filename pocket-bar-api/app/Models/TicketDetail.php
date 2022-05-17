<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TicketDetail extends Model
{
    use HasFactory;

    protected $table = "ticket_details_tbl";

    protected $fillable = ["*"];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, "ticket_id");
    }

    public function articulo()
    {
        return $this->belongsTo(Articulo::class, "articulos_tbl_id");
    }

    public static function getListForWebSockets(?string $status = null)
    {
        /**
         * @var \App\Models\User $user
         */
        $user = auth()->user();

        $actualWorkshift = Workshift::where('active', 1)->first();
        $users = [$user->id];
        if ($user->rol_id == 5) {
            array_push($users, ...array_column(User::where("rol_id", 4)->get(["id"])->toArray(), "id"));
        }

        $ticketDetails = self::with("articulo")->whereIn("user_id", $users)
            ->join("tickets_tbl", function ($join) use ($actualWorkshift) {
                $join->on("tickets_tbl.id", "=", "ticket_details_tbl.ticket_id")
                    ->where("tickets_tbl.workshift_id", "=", $actualWorkshift->id);
            })
            ->selectRaw("ticket_details_tbl.*");

        if ($user->rol_id == 5) {
            $ticketDetails = $ticketDetails->whereIn("ticket_details_tbl.status", ["En espera", "En preparacion", "Preparado"]);
        } else {
            $ticketDetails = $ticketDetails->where("ticket_details_tbl.status", "Preparado");
        }

        if ($status) {
            $ticketDetails = $ticketDetails->where("ticket_details_tbl.status", $status);
        }

        return $ticketDetails->get();
    }

    public static function countOfStatusOfTicket(int $ticketId): Collection
    {
        return self::selectRaw("status, count(status) as cantidad")
            ->groupBy("status")
            ->where("ticket_id", $ticketId)
            ->orderBy("cantidad")
            ->get();
    }

    public static function lastStatusOfTicket(int $ticketId, ?Collection $countOfStatusOfTicket = null): string
    {
        $products = isset($countOfStatusOfTicket) ? $countOfStatusOfTicket : self::countOfStatusOfTicket($ticketId);
        $status =  array_flip(array_column($products->toArray(), "status"));

        if (isset($status["En espera"]) or isset($status["En preparacion"]) or isset($status["Preparado"])) {
            return "En espera";
        }

        return "Entregado";
    }
}
