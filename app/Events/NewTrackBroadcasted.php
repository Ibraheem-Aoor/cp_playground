<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class NewTrackBroadcasted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tracks; // Array of tracks

    public function __construct(array $tracks)
    {
        $this->tracks = $tracks; // Array of tracks with URLs and titles
    }

    public function broadcastOn()
    {
        return new Channel('live-stream');
    }
}
