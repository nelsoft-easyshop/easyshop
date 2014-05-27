<?php

namespace EasyShop\WebSocket\Handler;

use Ratchet\Wamp\WampServerInterface;
use Ratchet\ConnectionInterface;
use EasyShop\Utility\StringUtility;



/**
 * Socket handler implementation v1.0; codename Zada.
 * 
 * 
 * 
 * @author czarpino
 */
class Zada implements WampServerInterface
{
    /**
     * @var StringUtility
     */
    private $stringUtility;
    
    /**
     * @var \React\Socket\ConnectionInterface
     */
    private $clients;
    
    /**
     * Constructor
     * 
     * @param \EasyShop\Utility\StringUtility $stringUtility
     */
    public function __construct(StringUtility $stringUtility)
    {
        $this->stringUtility = $stringUtility;
        $this->clients = [];
    }
    
    /**
     * No RPC allowed for client!
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param type $id
     * @param type $topic
     * @param array $params
     */
    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // reward client for trying the forbidden
        $conn->callError($id, $topic, 'Sorry, that is not allowed')
             ->close();
    }

    /**
     * Handle closed connections
     * 
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // Do nothing
    }

    /**
     * Handle errors
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param \Exception $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        // Log error perhaps? Otherwise, do nothing
    }

    /**
     * Handle newly opened connections. Only connections associated
     * with an authenticated session are allowed
     * 
     * @param \Ratchet\ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        $params = $this->stringUtility->paramsToArray($conn->WebSocket
                                                           ->request
                                                           ->getQuery());
        
        $hasSingleParam             = 1 === count($params);     // keep attack vectors low -- limit to a single param
        $hasIdParam                 = isset ($params['id']);    // look for session id in param `id`
        $isSessionAuthenticated     = true;                     // pretend we queried db
        
        if ($hasSingleParam && $hasIdParam && $isSessionAuthenticated) {
            $this->clients[$params['id']] = $conn;
            echo "Client connected\n";
        }
        else {
            echo "Unauthorized connection!\n";
            // Ideally log failed connections; some other time perhaps
            $conn->close();
        }
    }

    /**
     * No publishing allowed for client!
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param type $topic
     * @param type $event
     * @param array $exclude
     * @param array $eligible
     */
    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // reward client for trying the forbidden
        $conn->close();
    }

    /**
     * No subscriptions here
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param type $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        // Do nothing
    }

    /**
     * No subscriptions means no unsubsciptions either
     * 
     * @param \Ratchet\ConnectionInterface $conn
     * @param type $topic
     */
    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        // Do nothing
    }
    
    /**
     * Handle ZeroMQ pushes
     * 
     * @param string $serialData
     */
    public function onUpdate($serialData)
    {
        $data = json_decode($serialData, true);
        $sessionId = isset($data['session_id']) ? $data['session_id'] : false;
        
        if ($sessionId && $this->clients[$sessionId]) {
            $this->clients[$sessionId]->send($serialData);
        }
    }
}
