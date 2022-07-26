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

    public $afterCommit = true;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->TicketsARecibir = TicketDetail::getListForWebSockets(null, $userId, 4)->toArray();
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
