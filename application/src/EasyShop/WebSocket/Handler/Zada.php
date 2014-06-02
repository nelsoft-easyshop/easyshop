<?php

namespace EasyShop\WebSocket\Handler;

use Ratchet\Wamp\WampServerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
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
     * Topic array
     * 
     * @var array
     */
    private $topics;
    
    /**
     * Constructor
     * 
     * @param \EasyShop\Utility\StringUtility $stringUtility
     */
    public function __construct(StringUtility $stringUtility)
    {
        $this->stringUtility = $stringUtility;
        $this->topics = [];
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
            
            // Allow connection
            echo "Client connected\n";
        }
        else {
            
            // Terminate illegal connection -- ideally log failed connections; some other time perhaps
            echo "Unauthorized connection!\n";
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
     * @param Topic $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic)
    {
        if (!array_key_exists($topic->getId(), $this->topics)) {
            $this->topics[$topic->getId()] = $topic;
        }
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
    public function onPush($serialData)
    {
        $data = json_decode($serialData, true);
        
        if (isset ($data['session_id'])) {
            $this->topics[$data['session_id']]->broadcast($data);
        }
    }
    
    /**
     * Retrieve push handler method name
     * 
     * @return string
     */
    public function getHandlerMethod()
    {
        return 'onPush';
    }
    
    /**
     * Retrieve URL to listen to
     * 
     * @return string
     */
    public function getPushURL()
    {
        return 'tcp://127.0.0.1:5555';
    }
}
