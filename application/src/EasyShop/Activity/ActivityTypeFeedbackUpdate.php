<?php

namespace EasyShop\Activity;

class ActivityTypeFeedbackUpdate extends AbstractActivityType
{    
    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_USER = 0;

    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_PRODUCT = 1;
    
    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_PRODUCT_REPLY = 2;
    
    /**
     * Return if the action is valid
     *
     * @param integer $action
     * @return boolean
     */
    public function isUsableAction($action)
    {
        $actionArray = [
            self::ACTION_FEEDBACK_USER,
            self::ACTION_FEEDBACK_PRODUCT,
            self::ACTION_FEEDBACK_PRODUCT_REPLY,
        ];

        return in_array($action, $actionArray);
    }
}


