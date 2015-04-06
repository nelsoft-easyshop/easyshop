<?php

namespace EasyShop\Activity;

class ActivityTypeInformationUpdate extends AbstractActivityType
{
    /**
     * Return if the action is valid
     *
     * @param integer $action [dummy argument to conform with super class contract]
     * @return boolean
     */
    public function isUsableAction($action)
    {
        return true;
    }
}


