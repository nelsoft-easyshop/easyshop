<?php

namespace EasyShop\Activity;

class ActivityTypeProductUpdate
{
    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_PRODUCT_UPDATE = 0;
    
    /**
     * Action constant for product delete 
     *
     * @var integer
     */
    const ACTION_PRODUCT_SOFT_DELETE = 1;
    
    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_PRODUCT_FULL_DELETE = 2;
    
    /**
     * Action constant for product restore 
     *
     * @var integer
     */
    const ACTION_PRODUCT_RESTORE = 4;

    /**
     * Build JSON String contract
     *
     * @param integer $productId
     * @param intgeer $action
     * @return string
     */
    public function constructJSON($productId, $action)
    {
        $actionArray = [
            self::ACTION_PRODUCT_UPDATE,
            self::ACTION_PRODUCT_SOFT_DELETE,
            self::ACTION_PRODUCT_FULL_DELETE,
            self::ACTION_PRODUCT_RESTORE,
        ];
        
        $data = [];
        if(in_array($action, $actionArray)){
            $data['productId'] = $productId;
            $data['action'] = $action;
        }
        
        return json_encode($data);
    }
}


