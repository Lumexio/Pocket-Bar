<?php


namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

use App\Models\Workshift;

use Illuminate\Support\Carbon;

use App\Http\Requests\TicketCreateRequest;
use App\Models\Ticket;

class ticketCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $tickets;
    public $afterCommit = true;
    public function __construct()
    {
        /**
         * @var User
         */
        $user = auth()->user();
        $actualWorkshift = Workshift::where("active", 1)->first();

        $this->tickets = Ticket::with(['user', 'table', 'details.articulo', "workshift", "payments"])
            ->orderBy("ticket_date", "desc")
            ->where("status", "Por entregar")
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
                $data["productos"] = $ticket->details->map(function ($item) {
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
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('tickets');
    }
}