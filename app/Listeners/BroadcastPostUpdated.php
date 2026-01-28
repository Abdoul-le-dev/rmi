<?php

namespace App\Listeners;

use App\Events\PostUpdated;
use App\Services\WebSocketService;

class BroadcastPostUpdated
{
    public function handle(PostUpdated $event)
    {
        WebSocketService::broadcastPostUpdated($event->post);
    }
}