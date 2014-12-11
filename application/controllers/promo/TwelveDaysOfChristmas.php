<?php

class TwelveDaysOfChristmas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->em = $this->serviceContainer['entity_manager'];
        $this->promoManager = $this->serviceContainer['promo_manager'];
    }

    /**
     * Retrieve page for twelve Days Of Christmas Promo
     */
    public function twelveDaysOfChristmasPromo()
    {
        $product = $this->promoManager->getActiveDataForTwelveDaysOfChristmasByDate(new DateTime('now'));
        $this->promoManager->hydratePromoData($product);
        $promoData = [
            'product' => $product,
            'image' => $this->em->getRepository('EasyShop\Entities\EsProductImage')->getDefaultImage($product->getIdProduct())
        ];

        $this->load->view('/pages/web/christmas-promo', $promoData);
    }
}