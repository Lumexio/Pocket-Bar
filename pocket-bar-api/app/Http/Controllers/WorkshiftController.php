<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Helpers\WorkshiftReport;
use App\Http\Requests\Workshift\CloseRequest;
use App\Http\Requests\Workshift\StartRequest;
use App\Models\Ticket;
use App\Models\Workshift;
use App\Models\CashRegisterCloseData;
use App\Models\Payment;
use DB;
use Illuminate\Support\Collection;

class WorkshiftController extends Controller
{
    public function close(CloseRequest $request)
    {
        $activeWorkshift = Workshift::where('active', 1)->where("branch_id", auth()->user()->branch_id)->first();

        if (empty($activeWorkshift)) {
            return response()->json([
                'message' => 'No hay una jornada de trabajo activa'
            ], 400);
        }
        $pendingTickets = Ticket::whereNotIn("status", [TicketStatus::Closed->value, TicketStatus::Canceled->value])->where("workshift_id", $activeWorkshift->id)->get();
        $workshift_report = (new WorkshiftReport($activeWorkshift))->getWorkShiftReport();

        if ($pendingTickets->count() > 0) {
            return response()->json([
                'message' => "Hay cuentas pendientes de cerrar, Tienes pendientes de cerrar {$pendingTickets->count()} cuentas, falta por cobrar {$pendingTickets->sum('total')}",
                "workshift_report" => [...$workshift_report],
            ], 400);
        }

        $closedTickets = Ticket::whereIn("status", [TicketStatus::Closed->value])->get();
        $closeCashierData = CashRegisterCloseData::where('workshift_id', $activeWorkshift->id)->get();
        $totalPaid = $closedTickets->sum('total');
        $totalOfTickets = $closedTickets->sum('total');
        $difference = $totalPaid - $totalOfTickets;

        if ($difference < -1) {
            return response()->json([
                'message' => "La diferencia debe ser menor igual a la suma de las cuentas cerradas menos 1 peso, posiblemente falta hacer un corte de caja",
                "workshift_report" => [...$workshift_report],

            ], 400);
        }

        $activeWorkshift->active = 0;
        $activeWorkshift->end_money = $request->input('end_money');
        if ($activeWorkshift->save()) {
            return response()->json([
                'message' => 'Jornada de trabajo cerrada',
                "saldoFavor" => $difference,
                "workshift_report" => [...$workshift_report],
            ], 200);
        }
    }

    public function start(StartRequest $request)
    {
        $activeWorkshift = Workshift::where('active', 1)->where("branch_id", auth()->user()->branch_id)->first();
        if ($activeWorkshift) {
            return response()->json([
                'message' => 'Ya hay una jornada de trabajo activa'
            ], 400);
        }
        $workshift = new Workshift();
        $workshift->active = 1;
        $workshift->start_money = $request->input('start_money');
        $workshift->branch_id = auth()->user()->branch_id;
        $workshift->save();
        return response()->json([
            'message' => 'Jornada de trabajo iniciada'
        ], 200);
    }
}
