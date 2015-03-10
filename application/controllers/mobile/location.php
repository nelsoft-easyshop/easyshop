<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class location extends MY_Controller 
{
     /**
     * Mobile location constructor
     */
    function __construct() 
    {
        parent::__construct();
        header('Content-type: application/json');
    }

    /**
     * Get all location and arrange recursive based on it's parent location
     * format for shipping address
     * @return json
     */
    public function getLocationForAddress()
    {
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $locations = $apiFormatter->formatLocationForAddress();
    
        echo json_encode($locations,JSON_PRETTY_PRINT);
    }

    /**
     * Get all location and arrange recursive based on it's parent location
     * format for product shipping location
     * @return json
     */
    public function getLocationForShipping()
    {
        $apiFormatter = $this->serviceContainer['api_formatter'];
        $locations = $apiFormatter->formatLocationForShipping();

        echo json_encode($locations,JSON_PRETTY_PRINT);
    }
}
