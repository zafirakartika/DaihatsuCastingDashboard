#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class PusherServer implements MessageComponentInterface {
    protected $clients;
    protected $subscriptions;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
        $this->subscriptions = [];
        echo "🚀 Pusher-compatible WebSocket Server initialized\n";
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "✅ New connection: {$conn->resourceId}\n";

        // Send connection established message
        $conn->send(json_encode([
            'event' => 'pusher:connection_established',
            'data' => json_encode([
                'socket_id' => $conn->resourceId,
                'activity_timeout' => 120
            ])
        ]));
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo "📨 Received message from {$from->resourceId}: {$msg}\n";

        $data = json_decode($msg, true);

        if (!$data || !isset($data['event'])) {
            return;
        }

        switch ($data['event']) {
            case 'pusher:subscribe':
                $this->handleSubscribe($from, $data);
                break;

            case 'pusher:unsubscribe':
                $this->handleUnsubscribe($from, $data);
                break;

            case 'pusher:ping':
                $from->send(json_encode(['event' => 'pusher:pong']));
                break;
        }
    }

    protected function handleSubscribe(ConnectionInterface $conn, $data) {
        $channel = $data['data']['channel'] ?? null;

        if (!$channel) {
            return;
        }

        if (!isset($this->subscriptions[$channel])) {
            $this->subscriptions[$channel] = new \SplObjectStorage;
        }

        $this->subscriptions[$channel]->attach($conn);

        echo "📢 Client {$conn->resourceId} subscribed to: {$channel}\n";

        // Send subscription succeeded
        $conn->send(json_encode([
            'event' => 'pusher_internal:subscription_succeeded',
            'channel' => $channel,
            'data' => '{}'
        ]));
    }

    protected function handleUnsubscribe(ConnectionInterface $conn, $data) {
        $channel = $data['data']['channel'] ?? null;

        if ($channel && isset($this->subscriptions[$channel])) {
            $this->subscriptions[$channel]->detach($conn);
            echo "🔕 Client {$conn->resourceId} unsubscribed from: {$channel}\n";
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);

        // Remove from all subscriptions
        foreach ($this->subscriptions as $channel => $subscribers) {
            if ($subscribers->contains($conn)) {
                $subscribers->detach($conn);
            }
        }

        echo "❌ Connection closed: {$conn->resourceId}\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "⚠️  Error on {$conn->resourceId}: {$e->getMessage()}\n";
        $conn->close();
    }

    /**
     * Broadcast message to a specific channel
     */
    public function broadcast($channel, $event, $data) {
        if (!isset($this->subscriptions[$channel])) {
            echo "⚠️  No subscribers for channel: {$channel}\n";
            return;
        }

        $message = json_encode([
            'event' => $event,
            'channel' => $channel,
            'data' => $data
        ]);

        $count = 0;
        foreach ($this->subscriptions[$channel] as $client) {
            $client->send($message);
            $count++;
        }

        echo "📡 Broadcasted to {$count} clients on channel '{$channel}': {$event}\n";
    }
}

// Create WebSocket server
$pusherServer = new PusherServer();
$server = IoServer::factory(
    new HttpServer(
        new WsServer($pusherServer)
    ),
    6001,  // Port 6001 as configured in .env
    '0.0.0.0'
);

echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║   WebSocket Server (Pusher Protocol Compatible)           ║\n";
echo "║   Running on: ws://127.0.0.1:6001                         ║\n";
echo "║   Press Ctrl+C to stop                                     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$server->run();
