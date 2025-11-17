<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoomStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $roomId,
        public bool $isActive,
        public ?string $currentUserName,
        public ?int $currentUserId = null
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('room-status'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'room.status.updated';
    }
}
