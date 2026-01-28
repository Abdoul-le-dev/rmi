<?php

namespace App\Listeners;

use App\Events\PostShared;
use App\Services\WebSocketService;

class BroadcastPostShared
{
    public function handle(PostShared $event)
    {
        WebSocketService::broadcastPostShared($event->postId);
    }
}