<?php 

namespace EasyShop\Activity;

abstract class AbstractActivityType
{
    abstract public function isUsableAction($action);

    /**
     * Construct JSON String
     * @param mixed $fields
     * @param integer $action
     * @return string
     */
    public function constructJSON($fields, $action = null)
    {
        if(is_array($fields) === false){
            throw new \Exception('First parameter must be an array');
        }
        
        $data = [];
        if($this->isUsableAction($action)){
            $data = $fields;
            if($action !== null){
                $data['action'] = $action;
            }
        }
        
        return json_encode($data);
    }
    
}

