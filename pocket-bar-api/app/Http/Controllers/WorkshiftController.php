<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Http\Requests\Workshift\CloseRequest;
use App\Http\Requests\Workshift\StartRequest;
use App\Models\Ticket;
use App\Models\Workshift;
use App\Models\CashRegisterCloseData;
use App\Models\Payment;
use App\Models\TicketDetail;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class WorkshiftController extends Controller
{
    public Workshift $activeWorkshift;

    public function close(CloseRequest $request)
    {
        $this->activeWorkshift = Workshift::where('active', 1)->first();

        if (!$this->activeWorkshift) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }

        $workshift_report = [];
        $pendingTickets = Ticket::whereNotIn("status", [TicketStatus::Closed->value, TicketStatus::Canceled->value])->get();
<<<<<<< HEAD
        $totalGroupByEmployee = Ticket::selectRaw("SUM(total) as total_workshift_sales, SUM(tip) as totalTips")
            ->join("users as u", "u.id", "=", "tickets_tbl.user_id")
            ->join("rols_tbl as rol", "rol.id", "=", "u.rol_id")
            ->addSelect("u.id", "rol.name_rol as rol", "u.name")
            ->where("tickets_tbl.status", "=", TicketStatus::Closed->value)
            ->whereIn("rol.id", [4, 5])
            ->where('workshift_id', $this->activeWorkshift->id)
            ->groupBy(["tickets_tbl.user_id", "u.id", "rol.name_rol", "u.id", "u.name"])
            ->get();
        foreach ($totalGroupByEmployee as $totalEmployee) {
            $detailEmployee = [
                ...$totalEmployee->toArray()
            ];

            $this->getTickets([TicketStatus::Closed->value], $totalEmployee->id, $detailEmployee, "closed_tickets");
            $this->getTickets([TicketStatus::Delivered->value, TicketStatus::Standby->value], $totalEmployee->id, $detailEmployee, "non_closed_tickets");
            $this->getTickets([TicketStatus::Canceled->value], $totalEmployee->id, $detailEmployee, "canceled_tickets");
            $workshift_report[] = $detailEmployee;
        }

=======
>>>>>>> ad7b2d9 (fix: Ui redesing)
        if ($pendingTickets->count() > 0) {
            return response()->json([
                'message' => "Hay cuentas pendientes de cerrar, Tienes pendientes de cerrar {$pendingTickets->count()} cuentas, falta por cobrar {$pendingTickets->sum('total')}",
                "workshift_report" => $workshift_report,
            ], 400);
        }

        $closedTickets = Ticket::whereIn("status", [TicketStatus::Closed->value])->get();
        $closeCashierData = CashRegisterCloseData::where('workshift_id', $this->activeWorkshift->id)->get();
        $totalPaid = $closedTickets->sum('total');
        $totalOfTickets = $closedTickets->sum('total');
        $difference = $totalPaid - $totalOfTickets;

        if ($difference < -1) {
            return response()->json([
                'message' => "La diferencia debe ser menor igual a la suma de las cuentas cerradas menos 1 peso, posiblemente falta hacer un corte de caja",
                "workshift_report" => $workshift_report,
            ], 400);
        }

        $this->activeWorkshift->active = 0;

        if ($this->activeWorkshift->save()) {
            return response()->json([
                'message' => 'Jornada de trabajo cerrada',
                "saldoFavor" => $difference,
                "workshift_report" => $workshift_report,
            ], 200);
        }
    }

    public function getTickets(array $filter, int $employee_id, array &$detailEmployee, string $type)
    {
        $Tickets = Ticket::whereIn("status", $filter)
            ->addSelect("id", "status", "client_name", "ticket_date", "total", "table_name")
            ->where("user_id", $employee_id)
            ->where('workshift_id', $this->activeWorkshift->id)
            ->get();
        foreach ($Tickets as $Ticket) {
            $payments = Payment::where("ticket_id", $Ticket->id)->get();
            $detail = $this->getDetails($Ticket->id);
            $detailEmployee[$type][] = [
                ...$Ticket->toArray(),
                "details" => $detail->toArray(),
                "payments" => $payments->toArray()
            ];
        }
    }

    public function getDetails(int $ticketId): Collection
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
    public function start(StartRequest $request)
    {
        $activeWorkshift = Workshift::where('active', 1)->first();
        if ($activeWorkshift) {
            return response()->json([
                'message' => 'Ya hay una jornada de trabajo activa'
            ], 400);
        }
        $workshift = new Workshift();
        $workshift->active = 1;
        $workshift->save();
        return response()->json([
            'message' => 'Jornada de trabajo iniciada'
        ], 200);
    }
}
