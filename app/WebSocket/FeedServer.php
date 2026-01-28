<?php

namespace App\WebSocket;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\Log;
use React\EventLoop\LoopInterface;

class FeedServer implements MessageComponentInterface
{
    protected $clients;
    protected $userConnections;
    protected $redis;
    protected $loop;

    public function __construct(LoopInterface $loop)
    {
        $this->clients = new \SplObjectStorage;
        $this->userConnections = [];
        $this->loop = $loop;
        
        Log::info('✅ [FeedServer] Instance créée');
        
        // 🔥 Connexion Redis avec Predis Async
        $this->setupRedis();
    }

    protected function setupRedis()
    {
        try {
            $redis = new \Predis\Async\Client('tcp://127.0.0.1:6379', $this->loop);
            
            $redis->connect(function ($redis) {
                Log::info('✅ [FeedServer] Connecté à Redis');
                
                // 🔥 S'abonner au channel
                $redis->pubSubLoop(['websocket-events'], function ($event, $pubsub) {
                    if ($event->kind === 'message') {
                        Log::info('📨 [FeedServer] Message Redis reçu: ' . $event->payload);
                        
                        $data = json_decode($event->payload, true);
                        $this->broadcast($data);
                    } elseif ($event->kind === 'subscribe') {
                        Log::info('✅ [FeedServer] Abonné au channel: ' . $event->channel);
                    }
                });
            });
            
        } catch (\Exception $e) {
            Log::error('❌ [FeedServer] Erreur Redis: ' . $e->getMessage());
        }
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        
        $userId = $this->getUserIdFromConnection($conn);
        
        if ($userId) {
            $this->userConnections[$userId] = $conn;
        }
        
        Log::info("✅ New WebSocket connection: {$conn->resourceId}, Total: " . $this->clients->count());
        
        $this->sendInitialData($conn, $userId);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        try {
            $data = json_decode($msg, true);
            
            if (!isset($data['type'])) {
                return;
            }
            
            switch ($data['type']) {
                case 'ping':
                    $from->send(json_encode(['type' => 'pong']));
                    break;
            }
            
        } catch (\Exception $e) {
            Log::error("WebSocket message error: " . $e->getMessage());
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        
        foreach ($this->userConnections as $userId => $userConn) {
            if ($userConn === $conn) {
                unset($this->userConnections[$userId]);
                break;
            }
        }
        
        Log::info("Connection closed: {$conn->resourceId}, Remaining: " . $this->clients->count());
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        Log::error("WebSocket error: " . $e->getMessage());
        $conn->close();
    }

    public function broadcast($data)
    {
        $count = $this->clients->count();
        Log::info("📢 [FeedServer] Broadcasting to {$count} clients");
        
        if ($count === 0) {
            Log::warning("⚠️ No clients connected");
            return;
        }
        
        $message = json_encode($data);
        
        foreach ($this->clients as $client) {
            $client->send($message);
        }
        
        Log::info("✅ [FeedServer] Broadcast completed");
    }

    protected function sendInitialData(ConnectionInterface $conn, $userId)
    {
        try {
            $controller = new \App\Http\Controllers\PostController();
            $request = new \Illuminate\Http\Request(['limit' => 10]);
            
            $response = $controller->fetch($request);
            $data = json_decode($response->getContent(), true);
            
            $conn->send(json_encode([
                'type' => 'initial_data',
                'posts' => $data['posts'],
                'current_user' => $data['current_user']
            ]));
            
            Log::info("✅ Initial data sent");
            
        } catch (\Exception $e) {
            Log::error("❌ Error sending initial data: " . $e->getMessage());
        }
    }

    protected function getUserIdFromConnection(ConnectionInterface $conn)
    {
        try {
            $cookies = $conn->httpRequest->getHeader('Cookie');
            
            if (empty($cookies)) {
                return null;
            }
            
            parse_str(str_replace('; ', '&', $cookies[0]), $cookieArray);
            
            if (isset($cookieArray['laravel_session'])) {
                $sessionId = $cookieArray['laravel_session'];
                
                $sessionData = \Illuminate\Support\Facades\Session::getHandler()
                    ->read($sessionId);
                
                if ($sessionData) {
                    $data = unserialize($sessionData);
                    return $data['login_web_' . sha1('web')] ?? null;
                }
            }
            
            return null;
            
        } catch (\Exception $e) {
            Log::error("Error getting user: " . $e->getMessage());
            return null;
        }
    }
}