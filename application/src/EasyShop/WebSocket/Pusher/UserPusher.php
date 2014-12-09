<?php

namespace EasyShop\WebSocket\Pusher;

use Doctrine\ORM\EntityManager;



/**
 * Description of SessionPusher
 *
 * @author czarpino
 */
class UserPusher
{
    /**
     * @var ZMQContext->getSocket 
     */
    private $socket;
    
    /**
     * @var EntityManager 
     */
    private $em;
    
    /**
     * Class constructor
     * 
     * @param type $socket
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct($socket, EntityManager $em)
    {
        $this->socket = $socket;
        $this->em = $em;
    }
    
    /**
     * 
     * @param type $userId
     * @param \EasyShop\WebSocket\Pusher\DataPacket $dataContainer
     */
    public function push($userId, DataContainer $dataContainer)
    {
        $authenticatedSessions = $this->em->getRepository('\EasyShop\Entities\EsAuthenticatedSession')
                                          ->findBy(['member' => $userId]);
        $data = $dataContainer->getData();
        foreach ($authenticatedSessions as $authenticatedSession) {
            $data['session_id'] = $authenticatedSession->getSession()->getSessionId();
            $this->socket->send(json_encode($data));
        }
        
        return $this;
    }
}
