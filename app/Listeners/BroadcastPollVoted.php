<?php

namespace App\Listeners;

use App\Events\PollVoted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\WebSocketService;

class BroadcastPollVoted
{
    public function handle(PollVoted $event)
    {
        WebSocketService::broadcastPollVoted($event->pollId, $event->optionId);
    }
}
