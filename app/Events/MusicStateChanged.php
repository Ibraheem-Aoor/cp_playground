`
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MusicStateChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $action;
    public $trackId;
    public $currentTime;

    public function __construct($action, $trackId, $currentTime = 0)
    {
        $this->action = $action;
        $this->trackId = $trackId;
        $this->currentTime = $currentTime;
    }

    public function broadcastOn()
    {
        return new Channel('music-sync');
    }
}
