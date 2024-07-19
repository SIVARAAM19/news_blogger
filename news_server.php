<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use React\EventLoop\Factory;
use React\Socket\Server as SocketServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;

class NewsServer implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {

        if ($msg === 'fetch_news') {
            echo "on message\n";

            $this->fetchNewsAndSend($from);
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function fetchNewsAndSend(ConnectionInterface $conn) {

        echo "Fetching news and sending to client\n";

        $api = '6f83461279da4b1e89b110e0cc474ae9';
        $baseUrl = 'https://newsapi.org/v2/everything?q=';
        $query = 'education';

        $url = $baseUrl . $query . '&apiKey=' . $api;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CAINFO, 'C:\Users\Lenovo\Dropbox\xampp\apache\bin\curl-ca-bundle.crt');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $userAgent = 'NewsAggregator/1.0 PHP cURL/7.4';
        curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
        } else {
            $conn->send($response);
        }

        curl_close($ch);
    }
}

$loop = Factory::create();

$socket = new SocketServer('0.0.0.0:8080', $loop);
$server = new IoServer(
    new HttpServer(
        new WsServer(
            new NewsServer()
        )
    ),
    $socket,
    $loop
);

echo "WebSocket server started on port 8080\n";

$loop->run();
