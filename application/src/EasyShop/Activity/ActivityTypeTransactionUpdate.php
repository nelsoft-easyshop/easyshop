<?php

namespace EasyShop\Activity;

class ActivityTypeTransactionUpdate
{    
    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_BOUGHT = 1;

    /**
     * Action constant for transaction received 
     *
     * @var integer
     */
    const ACTION_RECEIVED = 1;
    
    /**
     * Action constant for transaction refunded 
     *
     * @var integer
     */
    const ACTION_REFUNDED = 2;
    
    /**
     * Action constant for transaction completed 
     *
     * @var integer
     */
    const ACTION_COD_COMPLETED = 3;
    
    /**
     * Action constant for transaction rejected 
     *
     * @var integer
     */
    const ACTION_REJECTED = 4;
    
       
    /**
     * Action constant for transaction unrejected 
     *
     * @var integer
     */
    const ACTION_UNREJECTED = 5;

           
    /**
     * Action constant for add shipment detail
     *
     * @var integer
     */
    const ACTION_ADD_SHIPMENT = 6;
    
    /**
     * Action constant for edit shipment detail
     *
     * @var integer
     */
    const ACTION_EDIT_SHIPMENT = 7;

    /**
     * Build JSON String contract
     *
     * @param integer $orderId
     * @param integer $orderProductId
     * @param intgeer $action
     * @return string
     */
    public function constructJSON($orderId, $orderProductId, $action)
    {
        $actionArray = [
            self::ACTION_COD_COMPLETED,
            self::ACTION_REFUNDED,
            self::ACTION_RECEIVED,
            self::ACTION_REJECTED,
            self::ACTION_UNREJECTED,
            self::ACTION_ADD_SHIPMENT,
            self::ACTION_EDIT_SHIPMENT,
        ];
        
        $data = [];
        if(in_array($action, $actionArray)){
            $data['orderId'] = $orderId;
            $data['action'] = $action;
            if($orderProductId !== null){
                $data['orderProductId'] = $orderProductId;
            }
        }
        
        return json_encode($data);
    }
}


