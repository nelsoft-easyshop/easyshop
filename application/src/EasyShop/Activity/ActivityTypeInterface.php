<?php 

namespace EasyShop\Activity;

/**
 * Activity Type Interface
 * This class is implemented by each activityType
 */
interface ActivityTypeInterface
{
    /**
     * Build JSON String contract
     *
     * @param mixed $changeSet
     * @return string
     */
    public function constructJSON($changeSet); 
}

