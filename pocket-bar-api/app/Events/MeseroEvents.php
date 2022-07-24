<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Workshift;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MeseroEvents implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $TicketsARecibir;

    public $userId;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $actualWorkshift = Workshift::where('active', 1)->first();
        $tickets = Ticket::where('user_id', $userId)
            ->where('workshift_id', $actualWorkshift->id)
            ->with('details')
            ->get();

        foreach ($tickets as $ticket) {
            $countOfStatusOfTicket = TicketDetail::countOfStatusOfTicket($ticket->id);
            $count = $countOfStatusOfTicket->sum('cantidad');
            $statusDisponibles = array_column($countOfStatusOfTicket->toArray(), 'status');
            if (in_array('Preparado', $statusDisponibles)) {
                if ($countOfStatusOfTicket->where('status', '==', 'Preparado')->first()->cantidad == $count) {
                    $this->TicketsARecibir[] = $ticket;
                }
            }
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('mesero.' . $this->userId);
    }
}
