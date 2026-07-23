<?php

require __DIR__ . '/../vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

class ChatServer implements MessageComponentInterface
{
    protected \SplObjectStorage $clients;

    public function __construct()
    {
        $this->clients = new SplObjectStorage();

        echo "=====================================\n";
        echo " Smart Mentoring WebSocket Server\n";
        echo " Running on ws://localhost:8080\n";
        echo "=====================================\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn, [

            "user_id" => null,

            "connected_at" => date("H:i:s")

        ]);

        echo "[OPEN] Connection {$conn->resourceId}\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);

        if (!$data) {
            return;
        }

        switch ($data['type']) {

            case "register":

                $info = $this->clients[$from];

                $info["user_id"] = $data["user_id"];

                $this->clients[$from] = $info;

                echo "[REGISTER] User {$data['user_id']} connected\n";

                break;

            case "message":

                $this->sendPrivateMessage($from, $data);

                break;
        }
    }

    private function sendPrivateMessage(ConnectionInterface $from, array $data)
    {
        foreach ($this->clients as $client) {

            $info = $this->clients[$client];

            if (
                $info["user_id"] == $data["receiver_id"]
            ) {

                $client->send(json_encode([

                    "type" => "message",

                    "sender_id" => $data["sender_id"],

                    "message" => $data["message"]

                ]));

                echo "[MESSAGE] {$data['sender_id']} -> {$data['receiver_id']}\n";

                break;
            }

        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        if ($this->clients->contains($conn)) {

            $info = $this->clients[$conn];

            echo "[DISCONNECT] User {$info['user_id']}\n";

            $this->clients->detach($conn);

        }

    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "[ERROR] " . $e->getMessage() . PHP_EOL;

        $conn->close();
    }
}

$server = IoServer::factory(

    new HttpServer(

        new WsServer(

            new ChatServer()

        )

    ),

    8080

);

$server->run();