<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BannedUser
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public int $length;

    public User $user;

    /**
     * Create a new event instance.
     *
     * @param User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $user
     * @param int $length
     */
    public function __construct(User|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model $user, int $length)
    {
        $this->user = $user;
        $this->length = $length;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
