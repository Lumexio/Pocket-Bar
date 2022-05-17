<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Workshift;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BarraEvents implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public array $tickets;

    public bool $afterCommit = true;

    public function __construct()
    {
        $this->tickets = TicketDetail::getListForWebSockets()->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('barra');
    }
}
