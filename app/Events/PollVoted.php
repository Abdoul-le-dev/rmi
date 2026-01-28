<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PollVoted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pollId;
    public $optionId;

    public function __construct($pollId, $optionId)
    {
        $this->pollId = $pollId;
        $this->optionId = $optionId;
    }
}
