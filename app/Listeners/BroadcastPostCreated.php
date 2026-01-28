<?php

namespace App\Listeners;

use App\Events\PostCreated;
use App\Services\WebSocketService;
use Illuminate\Support\Facades\Log;

class BroadcastPostCreated
{
    public function handle(PostCreated $event)
    {
        Log::info('🎧 [Listener] BroadcastPostCreated appelé pour post ID: ' . $event->post->id);
        
        WebSocketService::broadcastPostCreated($event->post);
        
        Log::info('✅ [Listener] Broadcast terminé');
    }
}