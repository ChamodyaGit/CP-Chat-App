<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Message එක broadcast වෙන්න ඕනේ channel එක
     */
    public function broadcastOn(): array
    {
        // Receiver ගේ ID එකට අදාළ private channel එකට message එක යවනවා
        return [
            new PrivateChannel('chat.' . $this->message->receiver_id),
        ];
    }
}
