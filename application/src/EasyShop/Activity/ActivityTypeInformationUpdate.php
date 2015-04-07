<?php

namespace EasyShop\Activity;

class ActivityTypeInformationUpdate extends AbstractActivityType
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return formatted data for specific activity
     *
     * @param string $jsonData
     * @return mixed
     */
    public function getFormattedData($jsonData)
    {
        $activityData = json_decode($jsonData);
        return $activityData;
    }
}


