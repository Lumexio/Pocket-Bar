<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class TicketDetail extends Model
{
    use HasFactory;

    protected $table = "ticket_details";

    protected $fillable = ["*"];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, "ticket_id");
    }

    public function product()
    {
        return $this->belongsTo(Product::class, "product_id");
    }

    public function mesero()
    {
        return $this->belongsTo(User::class, "waiter_id");
    }

    public function barra()
    {
        return $this->belongsTo(User::class, "barTender_id");
    }

    public static function getListForWebSockets(?string $status = null, int $userId, int $roleId): Collection
    {
        $actualWorkshift = Workshift::where('active', 1)->first();

        $users = [$userId];
        $relations = ["product", "barra", "mesero"];

        /**
         * Preguntamos si es un barra o un mesero
         */
        if ($roleId == 5) {
            array_push($users, ...array_column(User::where("rol_id", "!=", 5)->get(["id"])->toArray(), "id"));
            $relations = ["product", "mesero"];
            $ticketDetails = self::with($relations)
                ->whereIn("waiter_id", $users);
        } else {
            $ticketDetails = self::with($relations)
                ->where("waiter_id", $userId);
        }

        $ticketDetails = $ticketDetails
            ->join("tickets", function ($join) use ($actualWorkshift) {
                $join->on("tickets.id", "=", "ticket_details.ticket_id")
                    ->where("tickets.workshift_id", "=", $actualWorkshift->id)
                    ->where("tickets.status", "!=", "Cerrado");
            })
            ->selectRaw("ticket_details.*");

        if ($roleId == 5) {
            $ticketDetails = $ticketDetails->whereIn("ticket_details.status", ["En espera", "En preparacion"]);
        } else {
            $ticketDetails = $ticketDetails->where("ticket_details.status", "Preparado");
        }

        if ($status) {
            $ticketDetails = $ticketDetails->where("ticket_details.status", $status);
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
