<?php

namespace EasyShop\WebSocket;

/**
 * Description of Pusher
 *
 * @author czarpino
 */
class Pusher
{
    private $socket;
    
    private $data;
    
    public function __construct($socket)
    {
        $this->socket = $socket;
    }
    
    public function addData($key, $data)
    {
        $this->data[$key] = $data;
    }
    
    public function clearData()
    {
        $this->data = [];
        return $this;
    }
    
    public function pushToUserSession($sessionId)
    {
        $this->data['session_id'] = $sessionId;
        $this->socket->send(json_encode($this->data));
        return $this;
    }
    
    public function pushToAllUserSessions($userId)
    {
        if (false) {
            // push notif TODO: abstract
            $result2 = $this->messages_model->get_all_messages($userId, "Get_UnreadMsgs");
            $em = $this->serviceContainer['entity_manager'];
            $authenticatedSessions = $em->getRepository('\EasyShop\Entities\EsAuthenticatedSession')
                                          ->findBy(['member' => $userId]);
            $pusher = $this->serviceContainer['pusher'];
            $pusher->addData('messageCount', $result2['unread_msgs']);
            foreach ($authenticatedSessions as $authenticatedSession) {
                $pusher->pushToUserSession($authenticatedSession->getSession()->getSessionId());
            }
        }
    }
}
