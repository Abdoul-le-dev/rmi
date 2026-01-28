<?php

namespace App\Listeners;

use App\Events\PostDeleted;
use App\Services\WebSocketService;

class BroadcastPostDeleted
{
    public function handle(PostDeleted $event)
    {
        WebSocketService::broadcastPostDeleted($event->postId);
    }
}