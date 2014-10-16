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

        // Get all product
        $response['products'] = $searchProductService->getProductBySearch($parameter);

        // product display
        $productArray = array();

        foreach ($response['products'] as $key => $value) {
            $productArray[] = $this->serviceContainer['api_formatter']
                                                ->formatDisplayItem($value->getIdProduct());
        }

        $attributes = $searchProductService->getProductAttributesByProductIds($response['products']);
        $sortType = array('hot','new');

        $arrayDisplay = array(
                            'products' => $productArray,
                            'attributes' => $attributes,
                            'sort_available' => $sortType,
                        ); 

        print(json_encode($arrayDisplay,JSON_PRETTY_PRINT)); 
    }
}
