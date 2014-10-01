<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class location extends MY_Controller 
{
    function __construct() 
    {
        parent::__construct(); 
        $this->em = $this->serviceContainer['entity_manager'];
        header('Content-type: application/json');
    }

    public function getAllLocation()
    {
        // Load Repository 
        $EsLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $data['available_selection'] = $EsLocationLookupRepository->getLocationLookup();
 
        $modifiedArray = [];
        $counter = 0;
        foreach ($data['available_selection']['stateRegionLookup'] as $key => $value) {
            $modifiedArray[$counter]['regionId'] = $key; 
            $modifiedArray[$counter]['regionName'] = $value;
            $arrayCity = [];
            $cityCounter = 0;
            foreach ($data['available_selection']['cityLookup'][$key] as $keyCity => $valueCity) {
                $arrayCity[$cityCounter]['regionId'] = $keyCity; 
                $arrayCity[$cityCounter]['regionName'] = $valueCity;
                $cityCounter++;
            }
            $modifiedArray[$counter]['cities'] = $arrayCity;
            $counter++;
        }
    
        echo json_encode($modifiedArray,JSON_PRETTY_PRINT);
    }

}