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
        $configLoader = $this->serviceContainer['config_loader'];
        $promoConfig = $configLoader->getItem('promo', 'Promo');
        $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->findOneBy(['slug' => $slug, 'isDelete' => 0, 'isDraft' => 0]);

        if($product){
            $productId = $product->getIdProduct();
            $productCategoryId = $product->getCat()->getIdCat();
            $format = $this->serviceContainer['api_formatter']->formatItem($productId,true);
            $shippingDetails = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                                        ->getShippingDetailsByProductId($productId);

            $isButtonClickable = true;
            if((int) $product->getIsPromote() === EasyShop\Entities\EsProduct::PRODUCT_IS_PROMOTE_ON && (!$product->getEndPromo())){
                $isButtonClickable = $promoConfig[$product->getPromoType()]['viewable_button_product_page'];
            }

            $buttonLabel = "Add to Cart";
            if(count($shippingDetails) === 0 && (int)$product->getIsMeetup() === 1){
                $isButtonClickable = false;
                $buttonLabel = "Item is listed as an ad only. *";
            }
            elseif((int)$product->getPromoType() === \EasyShop\Entities\EsPromoType::BUY_AT_ZERO
                   && (bool) $product->getStartPromo()){
                $buttonLabel = "Click buy to qualify for the promo *";
            }
            elseif(!$isButtonClickable && (int)$product->getStartPromo() === 1){
                $buttonLabel = "This product is for promo use only.";
            }

            $format['productDetails']['isButtonClickable'] = $isButtonClickable;
            $format['productDetails']['buttonLabel'] = $buttonLabel;

            $relatedItems = $productManager->getRecommendedProducts($productId, 10);
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
                                     ->formatDisplayItem($item->getIdProduct());
        }

        print(json_encode($formattedDeals,JSON_PRETTY_PRINT));
    }
}

/* End of file product.php */
/* Location: ./application/controllers/mobile/product.php */
