<?php

namespace App\Helpers;

use App\Enums\Rol;
use App\Enums\TicketStatus;
use App\Models\Payment;
use App\Models\Ticket;
use App\Models\Workshift;
use DB;
use Illuminate\Support\Collection;

class WorkshiftReport
{

    private Workshift $activeWorkshift;

    public function __construct(Workshift $activeWorkshift)
    {
        $this->activeWorkshift = $activeWorkshift;
    }

    public function getWorkShiftReport(): array
    {
        $workshift_report = [];
        $total_debts = Ticket::selectRaw("SUM(total) as total_workshift_debt")
            ->join("users as u", "u.id", "=", "tickets_tbl.user_id")
            ->join("rols_tbl as rol", "rol.id", "=", "u.rol_id")
            ->addSelect("u.id", "rol.name_rol as rol", "u.name")
            ->where("tickets_tbl.status", TicketStatus::Delivered->value)
            ->whereIn("rol.id", [Rol::Bartender->value, Rol::Mesero->value])
            ->where('workshift_id', $this->activeWorkshift->id)
            ->groupBy(["rol.name_rol", "u.id", "u.name"])
            ->get();

        $totalGroupByEmployee = Ticket::selectRaw("SUM(total) as total_workshift_sales, SUM(tip) as totalTips")
            ->join("users as u", "u.id", "=", "tickets_tbl.user_id")
            ->join("rols_tbl as rol", "rol.id", "=", "u.rol_id")
            ->addSelect("u.id", "rol.name_rol as rol", "u.name")
            ->where("tickets_tbl.status", "=", TicketStatus::Closed->value)
            ->whereIn("rol.id", [4, 5])
            ->where('workshift_id', $this->activeWorkshift->id)
            ->groupBy(["rol.name_rol", "u.id", "u.name"])
            ->get();

        $ingresos = $this->activeWorkshift->generalIncoming()->with("user")->where("amount", ">", 0)->get();
        $egresos = $this->activeWorkshift->generalIncoming()->with("user")->where("amount", "<", 0)->get();
        $totalIngresos = $ingresos->sum("amount");
        $totalEgresos = $egresos->sum("amount");
        $workshift_report["ingresos"] = [
            "total" => $totalIngresos,
            "detail" => $ingresos,
        ];
        $workshift_report["egresos"] = [
            "total" => $totalEgresos,
            "detail" => $egresos,
        ];
        foreach ($totalGroupByEmployee as $totalEmployee) {
            $detailEmployee = $totalEmployee->toArray();

            self::getTickets([TicketStatus::Closed->value], $totalEmployee->id, $detailEmployee, "closed_tickets", $this->activeWorkshift->id);
            self::getTickets([TicketStatus::Delivered->value], $totalEmployee->id, $detailEmployee, "non_closed_tickets", $this->activeWorkshift->id);
            self::getTickets([TicketStatus::Canceled->value], $totalEmployee->id, $detailEmployee, "canceled_tickets", $this->activeWorkshift->id);
            $workshift_report["userTickets"][$totalEmployee->id] = $detailEmployee;
        }
        foreach ($total_debts as  $total_debt) {
            if (!isset($workshift_report["userTickets"][$total_debt->id])) {
                $detailEmployee = [
                    "total_workshift_sales" => 0,
                    "total_tips" => 0,
                    "user_id" => $total_debt->id,
                    "name" => $total_debt->name,
                    "rol_name" => $total_debt->rol,
                    "total_workshift_debt" => $total_debt->total_workshift_debt,
                    "closed_tickets" => [],
                ];
                self::getTickets([TicketStatus::Delivered->value], $total_debt->id, $detailEmployee, "non_closed_tickets", $this->activeWorkshift->id);
                self::getTickets([TicketStatus::Canceled->value], $total_debt->id, $detailEmployee, "canceled_tickets", $this->activeWorkshift->id);
                $workshift_report["userTickets"][$total_debt->id] = $detailEmployee;
            } else {
                $workshift_report["userTickets"][$total_debt->id]["total_workshift_debt"] = $total_debt->total_workshift_debt;
            }
        }
        $workshift_report["userTickets"] = array_values($workshift_report["userTickets"]);
        return $workshift_report;
    }

    private function getTickets(array $filter, int $employee_id, array &$detailEmployee, string $type, int $workshift_id)
    {
        $Tickets = Ticket::whereIn("status", $filter)
            ->addSelect("id", "status", "client_name", "ticket_date", "total")
            ->where("user_id", $employee_id)
            ->where('workshift_id', $workshift_id)
            ->get();
        $detailEmployee[$type] = [];
        foreach ($Tickets as $Ticket) {
            $payments = Payment::where("ticket_id", $Ticket->id)->get();
            $detail = self::getDetails($Ticket->id);
            $detailEmployee[$type][] = [
                ...$Ticket->toArray(),
                "details" => $detail->toArray(),
                "payments" => $payments->toArray()
            ];
        }
    }

    private function getDetails(int $ticketId): Collection
    {
        $details = DB::table("ticket_details_tbl as td")
            ->where("ticket_id", $ticketId)
            ->join("articulos_tbl as art", "art.id", "=", "td.articulos_tbl_id")
            ->addSelect(
                "art.nombre_articulo",
                "td.units",
                "td.unit_price",
                "td.discounts",
                "td.tax",
                "td.subtotal",
                "td.articulos_tbl_id as id_articulo",
                "td.articulos_img",
                "td.status",
                "td.barTender_id",
                "td.waiter_id",
                "td.ticket_id",
                "td.deleted_at",
                "td.created_at",
                "td.updated_at"
            )
            ->get();
        return $details;
    }
}
