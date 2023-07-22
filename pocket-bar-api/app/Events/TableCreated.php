<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

<<<<<<< HEAD:pocket-bar-api/app/Events/mesaCreated.php
class mesaCreated implements ShouldBroadcastNow
=======
class TableCreated
>>>>>>> 3bea85a (BREAKING CHANGE: refactor all spanish classes to english (include: request, controllers, models, migrations, factories and events)):pocket-bar-api/app/Events/TableCreated.php
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $mesas;
    public $afterCommit = true;
    public function __construct()
    {
        $this->mesas = \App::call('App\Http\Controllers\MesaController@index');
    }

    /**
      * Get the channels the event should broadcast on.
      *
      * @return \Illuminate\Broadcasting\Channel|array
      */
    public function broadcastOn(): Channel|array
    {
        return new Channel('mesas');
    }
}
