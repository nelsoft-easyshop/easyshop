<?php

namespace EasyShop\Activity;

class ActivityTypeProductUpdate extends AbstractActivityType
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
     * Return if the action is valid
     *
     * @param integer $action
     * @return boolean
     */
    public function isUsableAction($action)
    {
        $actionArray = [
            self::ACTION_PRODUCT_UPDATE,
            self::ACTION_PRODUCT_SOFT_DELETE,
            self::ACTION_PRODUCT_FULL_DELETE,
            self::ACTION_PRODUCT_RESTORE,
        ];

        return in_array($action, $actionArray);
    }
}


