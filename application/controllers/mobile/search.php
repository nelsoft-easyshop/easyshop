<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class search extends MY_Controller 
{
    function __construct()
    {
        parent::__construct();

        //Loading Helpers
        $this->load->helper('htmlpurifier');

        //Making response json type
        header('content-type: application/json');
    }

    /**
     * Search product in the given get parameters
     * @return json
     */
    public function searchItem()
    {
        // Load service
        $searchProductService = $this->serviceContainer['search_product'];

        $parameter = $this->input->get();
        $arrayDisplay = [
                'products' => [],
                'attributes' => [],
                'sort_available' => [],
            ]; 

        if($parameter){ 
            $search = $searchProductService->getProductBySearch($parameter); 
            $response['products'] = $search['collection'];
            $productArray = [];

            foreach ($response['products'] as $key => $value) {
                $productArray[] = $this->serviceContainer['api_formatter']
                                       ->formatDisplayItem($value->getIdProduct());
            }

            $attributes = $searchProductService->getProductAttributesByProductIds($search['productIds']);
            $sortType = ['name','price'];

            $specialFilter = ['filterprice' => 
                                [
                                    'P0 - P1000' => [
                                        'startprice' => 0,
                                        'endprice' => 1000,
                                    ],
                                    'P1000 - P5000' => [
                                        'startprice' => 1000,
                                        'endprice' => 5000,
                                    ],
                                    'P5000 - P10000' => [
                                        'startprice' => 5000,
                                        'endprice' => 10000,
                                    ],
                                    'P10000 and above' => [
                                        'startprice' => 10000,
                                        'endprice' => PHP_INT_MAX,
                                    ]
                                ]
                            ];
            $arrayDisplay = [
                        'products' => $productArray,
                        'filter' => $attributes,
                        'specialFilter' => $specialFilter,
                        'sort_available' => $sortType,
                    ]; 
        }


        print(json_encode($arrayDisplay,JSON_PRETTY_PRINT)); 
    }
}
