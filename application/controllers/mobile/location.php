<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class location extends MY_Controller 
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;
   
     /**
     * Mobile location constructor
     */
    function __construct() 
    {
        parent::__construct(); 
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');
    }

    /**
     * Get all location and arrange recursive based on it's parent location
     * @return json
     */
    public function getAllLocation()
    {
        // Load Repository 
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $data['available_selection'] = $EsLocationLookupRepository->getLocationLookup(); 
        $modifiedArray = [];
        $modifiedArray[0]['countryId'] = $data['available_selection']['countryId'];
        $modifiedArray[0]['coutryName'] = $data['available_selection']['countryName']; 
        $counter = 0;
        
        foreach ($data['available_selection']['stateRegionLookup'] as $key => $value) {
            $modifiedArray[0]['regions'][$counter]['regionId'] = $key; 
            $modifiedArray[0]['regions'][$counter]['regionName'] = $value;
            $arrayCity = [];
            $cityCounter = 0;
            foreach ($data['available_selection']['cityLookup'][$key] as $keyCity => $valueCity) {
                $arrayCity[$cityCounter]['cityId'] = $keyCity; 
                $arrayCity[$cityCounter]['cityName'] = $valueCity;
                $cityCounter++;
            }
            $modifiedArray[0]['regions'][$counter]['cities'] = $arrayCity;
            $counter++;
        }
    
        echo json_encode($modifiedArray,JSON_PRETTY_PRINT);
    }
}
