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


        $this->tickets = Ticket::with(['details.product:id,name,price', "workshift", "payments"])
            ->leftJoin('tables', 'tickets.table_id', '=', 'tables.id')
            ->leftJoin('users', 'tickets.user_id', '=', 'users.id')
            ->select('tickets.id', 'tickets.status', 'tickets.client_name', 'users.name', 'tickets.ticket_date', 'tickets.total', 'tickets.tip', 'tickets.cancel_confirm', 'tables.name')
            ->orderBy("ticket_date", "desc")
            ->get();

        // ->paginate(50, ['*'], 'page', $request->input('page', 1));

        return response()->json([
            "status" => 200,
            "error" => 0,
            "message" => "Listado de tickets",
            "data" => $this->tickets
        ], 200);
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
