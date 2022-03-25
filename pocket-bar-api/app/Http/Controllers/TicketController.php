<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\TicketCreateRequest;
use App\Http\Requests\TicketListPwaRequest;
use App\Models\Table;
use App\Models\Workshift;
use App\Models\User;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class TicketController extends Controller
{
    public function store(TicketCreateRequest $request): JsonResponse
    {
        $items = collect($request->input('items'));

        $subtotal = $items->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });

        $tax = $items->sum(function ($item) {
            return $item['tax'];
        });

        $discounts = $items->sum(function ($item) {
            return $item['discount'];
        });

        $total = $subtotal + $tax - $discounts;

        $table = Table::find($request->input('tableId'));
        DB::beginTransaction();
        try {
            $ticket = new Ticket();
            $ticket->table_id = $table->id;
            $ticket->table_name = $table->name;
            $ticket->status = "Por entregar";
            $ticket->client_name = $request->input('client_name');
            $ticket->user_id = auth()->user()->id;
            $ticket->ticket_date = date('Y-m-d H:i:s');
            $ticket->subtotal = $subtotal;
            $ticket->tip = $request->input('tip');
            $ticket->min_tip = $subtotal >= 500 ? $subtotal * 0.10 : $subtotal;
            $ticket->tax = $tax;
            $ticket->discounts = $discounts;
            $ticket->total = $total;
            $ticket->workshift_id = Workshift::where("active", 1)->firstOrFail()->id;

            throw_if(!$ticket->save(), \Exception::class, "Error al guardar el ticket");


            foreach ($items as $item) {
                $ticketDetail = new \App\Models\TicketDetail();
                $ticketDetail->units = $item['quantity'];
                $ticketDetail->unit_price = $item['price'];
                $ticketDetail->tax = $item['tax'];
                $ticketDetail->discounts = $item['discount'];
                $ticketDetail->total = $item['quantity'] * $item['price'] + $item['tax'] - $item['discount'];
                $ticketDetail->articulos_tbl_id = $item['id'];
                $ticketDetail->articulos_img = $item["image"];
                $ticketDetail->attended = 0;
                $ticketDetail->ticket_id = $ticket->id;
                throw_if(!$ticketDetail->save(), \Exception::class, "Error al guardar el detalle del ticket");
            }
            DB::commit();
        } catch (\Exception $th) {
            DB::rollBack();
            return response()->json(["status" => 500, "error" => 1, "message" => $th->getMessage()], 500);
        }

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Ticket creado correctamente",
            "data" => $ticket
        ], 200);
    }


    public function index(Request $request): JsonResponse
    {
        $tickets = Ticket::with(['details', "workshift", "payments"])
            ->orderBy("ticket_date", "desc")
            ->get();
        // ->paginate(50, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $tickets
        ], 200);
    }

    public function indexPwa(TicketListPwaRequest $request): JsonResponse
    {
        /**
         * @var User
         */
        $user = auth()->user();
        $actualWorkshift = Workshift::where("active", 1)->first();

        $tickets = Ticket::with(['user', 'table', 'details.articulo', "workshift", "payments"])
            ->orderBy("ticket_date", "desc")
            ->where("status", $request->input("status"))
            ->where("user_id", $user->id)
            ->where("workshift_id", $actualWorkshift->id ?? null)
            ->get()
            ->map(function (Ticket $ticket) {
                $data = [];
                $date = (new Carbon($ticket->ticket_date, "UTC"))->setTimezone($ticket->timezone);
                $data["id"] = $ticket->id;
                $data["mesa"] = $ticket->table_name;
                $data["estatus"] = $ticket->status;
                $data["titular"] = $ticket->client_name;
                $data["total"] = $ticket->total;
                $data["fecha"] = $date->toDateString();
                $data["cantidad_articulos"] = $ticket->details->count();
                $data["tiempo"] = $date->toTimeString("minute");
                $data["productos"] = $ticket->details->map(function ($item){
                   return [
                       "id" => $item->id,
                       "nombre" => $item->articulo->nombre_articulo,
                       "cantidad" => $item->units,
                       "precio" => $item->unit_price,
                       "subtotal" => $item->subtotal,
                       "total" => $item->total,
                       "descuento" => $item->discounts,
                       "iva" => $item->tax,
                   ];
                });
                $data["pagos"] = $ticket->payments ?? null;

                return $data;
            });

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $tickets
        ], 200);
    }
}
