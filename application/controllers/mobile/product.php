<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller {


    function __construct()
    {
        parent::__construct();

        //Loading Helpers
        $this->load->helper('htmlpurifier');

        //Loading Models
        $this->load->model('product_model');   

        //Making response json type
        header('Content-type: application/json');

        //Load service 
        $this->em = $this->serviceContainer['entity_manager'];
    }

    /**
     * Retrieve product information based on given slug
     * @param  string $slug
     * @return JSON
     */
    public function item($slug = '')
    {
        $productRow = $this->product_model->getProductBySlug($slug);  
        $id = $productRow['id_product'];
        $productCategoryId = $productRow['cat_id'];

        $format = $this->serviceContainer['api_formatter']->formatItem($id);

        $relatedItems = $this->product_model->getRecommendeditem($productCategoryId,5,$id);
        $formattedRelatedItems = array();
        foreach ($relatedItems as $key => $value) {
            $formattedRelatedItems[] = $this->serviceContainer['api_formatter']
                                                ->formatDisplayItem($value['id_product']);
        }
        $format = array_merge($format,array('relatedItems'=>$formattedRelatedItems));  

        print(json_encode($format,JSON_PRETTY_PRINT));
    }

    /**
     * Display all product in deals(category = 1000)
     * @return json
     */
    public function deals()
    {   
        $arrayFilter = array(
                        'isDelete' => 0,
                        'isDraft' => 0,
                        'cat' => 1000,
                    );
        $dealsItems = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->findBy($arrayFilter);
        $formattedDeals = array();
        foreach ($dealsItems as $key => $value) {
            $formattedDeals[] = $this->serviceContainer['api_formatter']
                                                ->formatDisplayItem($value->getIdProduct());
        }

        print(json_encode($formattedDeals,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/mobile/product.php */
