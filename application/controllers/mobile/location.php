<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

use EasyShop\Entities\EsLocationLookup as EsLocationLookup;

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
     * format for shipping address
     * @return json
     */
    public function getLocationForAddress()
    {
        // Load Repository 
        $esLocationLookupRepository = $this->em->getRepository('EasyShop\Entities\EsLocationLookup');
        $data['available_selection'] = $esLocationLookupRepository->getLocationLookup(); 
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

    /**
     * Get all location and arrange recursive based on it's parent location
     * format for product shipping location
     * @return json
     */
    public function getLocationForShipping()
    {
        $location = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                             ->getLocation();

        $formedArray = [];
        foreach ($location['area'] as $majorIsland => $region) {
            $regionArray = [];
            foreach ($region as $regionKey => $province) {
                $provinceArray = [];
                foreach ($province as $key => $value) {
                    $provinceArray[] = [
                        'name' => $value,
                        'location_id' => $key,
                        'children' => [],
                    ];
                }
                $regionArray[] = [
                    'name' => $regionKey,
                    'location_id' => $location['regionkey'][$regionKey],
                    'children' => $provinceArray,
                ];
            }

            $array = [
                'name' => $majorIsland,
                'location_id' => $location['islandkey'][$majorIsland],
                'children' => $regionArray,
            ];
            $formedArray[] = $array;
        }

        $finalArray = [
            'name' => 'Philippines',
            'location_id' => EsLocationLookup::PHILIPPINES_LOCATION_ID,
            'children' => $formedArray,
        ];

        echo json_encode($finalArray,JSON_PRETTY_PRINT);
    }
}
