<?php

namespace App\Services;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory as LoopFactory;
use React\Socket\SocketServer;
use App\WebSocket\FeedServer;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class WebSocketService
{
    public static function start($port = 8080)
    {
        $loop = LoopFactory::create();

        $feedServer = new FeedServer($loop);

        // ✅ LE SOCKET REACT (OBLIGATOIRE)
        $socket = new SocketServer("0.0.0.0:$port", [], $loop);

        $server = new IoServer(
            new HttpServer(
                new WsServer($feedServer)
            ),
            $socket,
            $loop
        );

        echo "🚀 WebSocket server running on ws://0.0.0.0:$port\n";
        echo "📡 Listening to Redis channel 'websocket-events'\n";

        $loop->run();
    }

    // ============================
    // REDIS PUBLISHERS (OK)
    // ============================

   public static function broadcastPostCreated($post)
{
    Log::info('🚀 [WebSocketService] Publishing to Redis for post ID: ' . $post->id);
    
    try {
        \Illuminate\Support\Facades\Redis::publish('websocket-events', json_encode([
            'type' => 'post.created',
            'post' => $post
        ]));
        
        Log::info('✅ [WebSocketService] Published to Redis');
    } catch (\Exception $e) {
        Log::error('❌ [WebSocketService] Redis error: ' . $e->getMessage());
    }
}

    public static function broadcastPostDeleted($postId)
    {
        Redis::publish('websocket-events', json_encode([
            'type' => 'post.deleted',
            'post_id' => $postId
        ]));
    }

    public static function broadcastPollVoted($pollId, $optionId)
    {
        Redis::publish('websocket-events', json_encode([
            'type' => 'poll.voted',
            'poll_id' => $pollId,
            'option_id' => $optionId
        ]));
    }

    public static function broadcastPostShared($postId)
    {
        Redis::publish('websocket-events', json_encode([
            'type' => 'post.shared',
            'post_id' => $postId
        ]));
    }
}
