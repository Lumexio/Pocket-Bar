<?php

namespace App\Http\Controllers;

use App\Events\BarraEvents;
use App\Events\MeseroEvents;
use App\Http\Requests\Ordenes\ProductoListRequest;
use App\Http\Requests\Ordenes\ProductoUpdateStatusRequest;
use App\Models\Ticket;
use App\Models\TicketDetail;
use Auth;
use DB;
use Illuminate\Http\JsonResponse;

class OrdenesController extends Controller
{
    public function index(ProductoListRequest $request): JsonResponse
    {
        return response()->json(TicketDetail::getListForWebSockets($request->input('status'), Auth::user()->id, Auth::user()->rol_id));
    }


    public function updateStatus(ProductoUpdateStatusRequest $request): JsonResponse
    {
        /**
         * @var \App\Models\User $user
         */
        $user = $request->user();

        if ($user->rol_id == 4 and $request->input("status") != "Recibido") {
            return response()->json([
                "error" => "No puedes cambiar el estado de un producto a menos que sea Recibido"
            ], 400);
        }

        $ticketDetail = TicketDetail::find($request->input("id"));
        $ticket = Ticket::find($ticketDetail->ticket_id);

        if ($ticket->status == "Cerrado") {
            return response()->json([
                "error" => "No puedes cambiar el estado de un producto de un ticket cerrado"
            ], 400);
        }

        if ($request->input("status") == "Recibido" and $ticketDetail->waiter_id != $user->id) {
            return response()->json([
                "error" => "No puedes cambiar el estado de un producto a Recibido pues no eres el mesero que lo solicitó"
            ], 400);
        }

        if ($ticketDetail->status == "Recibido") {
            return response()->json([
                "error" => "No puedes cambiar el estado de un producto que ya ha sido recibido anteriormente"
            ], 400);
        }

        try {
            if (in_array($ticketDetail->status, ["En espera", "Preparado"])) {
                $ticketDetail->barTender_id = $user->id;
            }


            $ticketDetail->status = $request->input("status");
            throw_if(!$ticketDetail->save(), "Error al guardar en base de datos");

            $countOfStatusOfTicket = TicketDetail::countOfStatusOfTicket($ticket->id);
            $previousStatus = $ticketDetail->status;
            $ticket->status = TicketDetail::lastStatusOfTicket($ticket->id, $countOfStatusOfTicket);
            if ($previousStatus != $ticket->status) {
                throw_if(!$ticket->save(), "Error al guardar en base de datos");
            }
        } catch (\Throwable $th) {

            return response()->json([
                "error" => $th->getMessage()
            ], 500);
        }


        broadcast(new MeseroEvents($ticketDetail->waiter_id))->toOthers();
        TicketController::sendNotificationsToBarthenders();


        return response()->json($ticketDetail);
    }
}
