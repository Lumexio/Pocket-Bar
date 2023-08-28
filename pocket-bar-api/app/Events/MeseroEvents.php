<?php

namespace App\Events;

use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\Workshift;
use BeyondCode\LaravelWebSockets\WebSockets\Channels\PrivateChannel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Log;

class MeseroEvents implements ShouldBroadcastNow
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
        $this->TicketsARecibir = TicketDetail::getListForWebSockets(null, $userId, 4)->toArray();
        Log::info("Tickets a recibir para  " . json_encode($this->TicketsARecibir));
        return $this->TicketsARecibir;
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
