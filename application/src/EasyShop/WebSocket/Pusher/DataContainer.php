<?php

namespace EasyShop\WebSocket\Pusher;



/**
 * Container for data to be pushed by Pusher
 *
 * @author czarpino
 */
class DataContainer
{    
    /**
     * @var array
     */
    private $data;
    
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->data = [];
    }
    
    /**
     * Add/set data
     * 
     * @param type $key
     * @param type $data
     * @return \EasyShop\WebSocket\Pusher\DataPacket
     * @throws \Exception
     */
    public function set($key, $data)
    {
        $this->data[$key] = $data;
        return $this;
    }
    
    /**
     * Retrieve data
     * 
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
