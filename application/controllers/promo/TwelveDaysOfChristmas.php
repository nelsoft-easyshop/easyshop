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
        $dateTimeToday = new DateTime('now');
        $product = $this->promoManager->getActiveDataForTwelveDaysOfChristmasByDate($dateTimeToday);
        $image = false;
        if ($product) {
            $image = $this->em->getRepository('EasyShop\Entities\EsProductImage')->getDefaultImage($product->getIdProduct());
            $this->promoManager->hydratePromoData($product);
        }
        $promoData = [
            'product' => $product,
            'image' => $image,
            'featuredVendor' => $this->getFeaturedVendorData($dateTimeToday)
        ];
        $this->load->view('/pages/web/christmas-promo', $promoData);

    }

    /**
     * Get featured vendor slug and image by date
     * @param $dateTimeToday
     * @return array
     */
    private function getFeaturedVendorData($dateTimeToday)
    {
        $dateTimeToday = $dateTimeToday->format('Y-m-d');
        $vendorDataContainer = [
            '2014-12-14' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-15' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-16' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-17' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-18' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-19' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-20' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-21' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-22' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-23' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-24' => [
                'slug' => '',
                'imageUrl' => ''
            ],
            '2014-12-25' => [
                'slug' => '',
                'imageUrl' => ''
            ],
        ];
        $featuredVendorData = [];
        foreach ($vendorDataContainer as $date => $vendorData) {
            if ($dateTimeToday === $date) {
                $featuredVendorData = $vendorData;
            }
        }

        return $featuredVendorData;
    }

}
