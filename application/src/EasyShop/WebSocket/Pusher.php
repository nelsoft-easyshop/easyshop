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
        
    }
}
