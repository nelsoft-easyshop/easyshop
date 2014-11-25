<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Product extends MY_Controller {


    function __construct()
    {
        parent::__construct();

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
        $productManager = $this->serviceContainer['product_manager'];
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->findOneBy(['slug' => $slug]);

        if($product){
            $productId = $product->getIdProduct();
            $productCategoryId = $product->getCat()->getIdCat();

            $format = $this->serviceContainer['api_formatter']->formatItem($productId,true);
            $relatedItems = $productManager->getRecommendedProducts($productId,5);
            $formattedRelatedItems = [];
            foreach ($relatedItems as $item) {
                $formattedRelatedItems[] = $this->serviceContainer['api_formatter']
                                                ->formatDisplayItem($item->getIdProduct());
            }
            $format = array_merge($format,['relatedItems' => $formattedRelatedItems]);

            print(json_encode($format,JSON_PRETTY_PRINT));
        }
        else{
            print(json_encode('product not exist!',JSON_PRETTY_PRINT));
        }
    }

    /**
     * Display all product in deals(category = 1000)
     * @return json
     */
    public function deals()
    {   
        $arrayFilter = [
            'isDelete' => 0,
            'isDraft' => 0,
            'cat' => 1000,
        ];
        $dealsItems = $this->em->getRepository('EasyShop\Entities\EsProduct')
                               ->findBy($arrayFilter);
        $formattedDeals = [];
        foreach ($dealsItems as $item) {
            $formattedDeals[] = $this->serviceContainer['api_formatter']
                                     ->formatDisplayItem($value->getIdProduct());
        }

        print(json_encode($formattedDeals,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/mobile/product.php */
