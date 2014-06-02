<?php

namespace EasyShop\WebSocket;

use React\EventLoop\LoopInterface;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;


/**
 * Web socket server
 *
 * @author czarpino
 */
class WebSocketServer
{
    /**
     * @var \React\EventLoop\LoopInterface
     */
    private $eventLoop;
    
    /**
     * @var WampServerInterface
     */
    private $socketHandler;
    
    /**
     * Constructor
     * 
     * @param \React\EventLoop\LoopInterface $eventLoop
     * @param \Ratchet\Wamp\WampServerInterface $requestHandler
     */
    public function __construct(LoopInterface $eventLoop, WampServerInterface $requestHandler)
    {
        $this->eventLoop = $eventLoop;
        $this->socketHandler = $requestHandler;
    }
    
    /**
     * Listen for ZeroMQ pushes
     * 
     * @param string $handlerFunction
     * @param string $netUrl
     */
    public function listenToPusher($handlerFunction, $netUrl = 'tcp://127.0.0.1:5555')
    {
        $zmqServer = new \React\ZMQ\Context($this->eventLoop);
        @$zmqServer->bind($netUrl);
        @$zmqServer->on('message', [$this->socketHandler, $handlerFunction]);
    }
    
    /**
     * Listen to client connections
     * 
     * @param int $port
     * @param string $ip
     * 
     * @return \Ratchet\Server\IoServer
     */
    public function listenToClient($port = 8080, $ip = '0.0.0.0')
    {
        $reactServer = new \React\Socket\Server($this->eventLoop);
        $reactServer->listen($port, $ip);
        
        return new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer($this->socketHandler)
                )
            ),
            $reactServer
        );
    }
    
    /**
     * Start event loop to begin recieving events
     */
    public function start()
    {
        $this->eventLoop->run();
    }
}