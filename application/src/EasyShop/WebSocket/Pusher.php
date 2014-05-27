<?php

namespace EasyShop\WebSocket;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use EasyShop\Utility\StringUtility;

/**
 * Web socket pusher
 *
 * @author czarpino
 */
class Pusher implements WampServerInterface 
{
    /**
     * @var array
     */
    private $topicSubscribers = [];
    
    private $stringUtility;
    
    /**
     * Constructor
     * 
     * @param \EasyShop\Utility\StringUtility $stringUtility
     */
    public function __construct(StringUtility $stringUtility)
    {
        $this->stringUtility = $stringUtility;
    }
    
    
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onClose(ConnectionInterface $conn) {
        
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        
    }

    /**
     * Handle connecting clients
     * 
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn) {
        echo "Client " . $conn->resourceId . " has connected' \n";
        echo "\t" . $conn->WebSocket->request->getQuery() . "\n";
        
        $params = $this->stringUtility->paramsToArray($conn->WebSocket->request->getQuery());
        
        $sessionId = '123fds'; // Retrieve this from database
        
        /* reject requests with extra parameters and those without a valid session id */
        // motivation: reduce attack vectors
        if (1 < count($params) || !isset ($params['id']) || $sessionId !== $params['id']) {
            
            echo "Closing an unauthorized connection...\n";
            $conn->close();
        }
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onSubscribe(ConnectionInterface $conn, $topic) {
        
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        
    }
    
    public function onBlogEntry($entry)
    {
        
    }
}
