<?php

namespace EasyShop\Activity;

class ActivityTypeTransactionUpdate extends AbstractActivityType
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
     * Return if the action is valid
     *
     * @param integer $action
     * @return boolean
     */
    public function isUsableAction($action)
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

        return in_array($action, $actionArray);
    }
}


