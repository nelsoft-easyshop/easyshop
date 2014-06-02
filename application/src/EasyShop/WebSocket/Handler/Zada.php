<?php

namespace EasyShop\WebSocket\Handler;

use Ratchet\Wamp\WampServerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use EasyShop\Utility\StringUtility;
use Doctrine\ORM\EntityManager;



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
     * @var EntityManager
     */
    private $em;
    
    /**
     * Constructor
     * 
     * @param \EasyShop\Utility\StringUtility $stringUtility
     */
    public function __construct(StringUtility $stringUtility, EntityManager $em)
    {
        $this->stringUtility = $stringUtility;
        $this->em = $em;
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
        try {
            $isSessionAuthenticated = false;
            $params = $this->stringUtility->paramsToArray($conn->WebSocket
                                                               ->request
                                                               ->getQuery());
            
            if (isset ($params['id'])) {
                $as = $this->em->getRepository('\Easyshop\Entities\AuthenticatedSession')
                               ->findOneBy(['session' => $params['id']]);
                $isSessionAuthenticated = NULL !== $as;
            }

            if (!$isSessionAuthenticated) {
                
                // Terminate illegal connection
                echo "Unauthorized connection!\n";
                $conn->close();
            }
            else {
                
                // Allow connection
                echo "Client connected\n";
            }
        } catch (\Exception $e) {
            // TODO log exception
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
