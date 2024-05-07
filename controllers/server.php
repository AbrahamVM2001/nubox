<?php
require __DIR__ . '/../public/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
        echo "Nuevo cliente conectado ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        if (isset($data['comentario'])) {
            $this->notifyNewComment($data);
        } elseif (isset($data['pregunta'])) {
            $this->notifyNewQuestion($data);
        }elseif(isset($data['votacion'])){
            $this->notifyNewVotacion($data);
        }elseif (isset($data['cierre'])) {
            $this->notifyNewCierre($data);
        }elseif(isset($data['acceso'])){
            $this->notifyNewAcceso($data);
        }elseif(isset($data['ponente'])){
            $this->notifyNewPonente($data);
        }
        foreach ($this->clients as $client) {
            if ($client !== $from) {
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        echo "Cliente desconectado ({$conn->resourceId})\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }

    public function notifyNewComment($commentData)
    {
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
    public function notifyNewQuestion($commentData) {
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
    public function notifyNewVotacion($commentData){
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
    public function notifyNewCierre($commentData){
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
    public function notifyNewAcceso($commentData){
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
    public function notifyNewPonente($commentData){
        foreach ($this->clients as $client) {
            $client->send(json_encode($commentData));
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "Servidor WebSocket iniciado en el puerto 8080\n";

$server->run();
