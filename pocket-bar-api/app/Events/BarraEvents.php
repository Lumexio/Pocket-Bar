<?php

namespace App\Events;

use App\Models\TicketDetail;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BarraEvents implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notificacionesBarra;

    public $user_id;

    public $afterCommit = true;

    public function __construct(int $userId, int $roleId)
    {
        $this->user_id = $userId;
        $this->notificacionesBarra = TicketDetail::getListForWebSockets(null, $userId, $roleId)->toArray();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('barra.' . $this->user_id);
    }
}
