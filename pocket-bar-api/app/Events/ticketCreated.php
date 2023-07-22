<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\Workshift;
use App\Models\Ticket;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

class TicketCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tickets;
    public $userId;
    public $afterCommit = true;
    public function __construct()
    {

        // $this->tickets = Ticket::with(['details', "workshift", "payments"])
        // ->orderBy("ticket_date", "desc")
        // ->get();

        $this->tickets = Ticket::with(['details.articulo:id,nombre_articulo,precio_articulo', "workshift", "payments"])
            ->leftJoin('mesas_tbl', 'tickets_tbl.mesa_id', '=', 'mesas_tbl.id')
            ->select('tickets_tbl.id', 'tickets_tbl.status', 'tickets_tbl.client_name', 'tickets_tbl.user_name', 'tickets_tbl.ticket_date', 'tickets_tbl.total', 'tickets_tbl.tip', 'tickets_tbl.specifictip', 'tickets_tbl.cancel_confirm', 'mesas_tbl.nombre_mesa')
            ->orderBy("ticket_date", "desc")
            ->get();
        // ->paginate(50, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $this->tickets
        ], 200);

        // $user = auth()->user();
        // $actualWorkshift = Workshift::where("active", 1)->first();
        // $this->tickets = Ticket::with(['user', 'table', 'details.articulo', "workshift", "payments"])
        // ->orderBy("ticket_date", "desc")
        //     //->where("status", "Por entregar")
        //     //->where("user_id", $user->id)
        //     ->where("workshift_id", $actualWorkshift->id ?? null)
        //     ->get()
        //     ->map(function (Ticket $ticket) {
        //         $data = [];
        //         $date = (new Carbon($ticket->ticket_date, "UTC"))->setTimezone($ticket->timezone);
        //         $data["id"] = $ticket->id;
        //         $data["mesa"] = $ticket->table_name;
        //         $data["status"] = $ticket->status;
        //         $data["titular"] = $ticket->client_name;
        //         $data["total"] = $ticket->total;
        //         $data["fecha"] = $date->toDateString();
        //         $data["cantidad_articulos"] = $ticket->details->count();
        //         $data["tiempo"] = $date->toTimeString("minute");
        //         $data["productos"] = $ticket->details->map(function ($item) {
        //             return [
        //                 "id" => $item->id,
        //                 "nombre" => $item->articulo->nombre_articulo,
        //                 "cantidad" => $item->units,
        //                 "precio" => $item->unit_price,
        //                 "subtotal" => $item->subtotal,
        //                 "total" => $item->total,
        //                 "descuento" => $item->discounts,
        //                 "iva" => $item->tax,
        //             ];
        //         });
        //         $data["pagos"] = $ticket->payments ?? null;

        //         return $data;
        //     });


        // $this->userId = $userId;
        // $actualWorkshift = Workshift::where("active", 1)->first();
        // $this->tickets = Ticket::with(['user', 'table', 'details.articulo', "workshift", "payments"])
        // ->orderBy("ticket_date", "desc")
        // ->where("status", "Por entregar")
        // ->where("user_id", $userId)
        //     ->where("workshift_id", $actualWorkshift->id ?? null)
        //     ->get()
        //     ->map(function (Ticket $ticket) {
        //         $data = [];
        //         $date = (new Carbon($ticket->ticket_date, "UTC"))->setTimezone($ticket->timezone);
        //         $data["id"] = $ticket->id;
        //         $data["mesa"] = $ticket->table_name;
        //         $data["estatus"] = $ticket->status;
        //         $data["titular"] = $ticket->client_name;
        //         $data["total"] = $ticket->total;
        //         $data["fecha"] = $date->toDateString();
        //         $data["cantidad_articulos"] = $ticket->details->count();
        //         $data["tiempo"] = $date->toTimeString("minute");
        //         $data["productos"] = $ticket->details->map(function ($item) {
        //             return [
        //                 "id" => $item->id,
        //                 "nombre" => $item->articulo->nombre_articulo,
        //                 "cantidad" => $item->units,
        //                 "precio" => $item->unit_price,
        //                 "subtotal" => $item->subtotal,
        //                 "total" => $item->total,
        //                 "descuento" => $item->discounts,
        //                 "iva" => $item->tax,
        //             ];
        //         });
        //         $data["pagos"] = $ticket->payments ?? null;

        //         return $data;
        //     });

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tickets.' . $this->userId);
    }
}
