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
        if ($product) {
            $this->promoManager->hydratePromoData($product);
        }
        $promoData = [
            'product' => $product,
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
            '2014-12-12' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'item1.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-15' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'item2.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-16' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'item3.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-17' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'item4.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-18' => [
                'slug' => 'sansoncellshop',
                'vendorImageUrl' => 'item5.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-19' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'item6.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-20' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'item7.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-21' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'item8.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-22' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'item9.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-23' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'item10.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-24' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'item11.jpg',
                'productImageUrl' => ''
            ],
            '2014-12-25' => [
                'slug' => 'sansoncellshop',
                'vendorImageUrl' => 'item12.jpg',
                'productImageUrl' => ''
            ],
        ];
        $featuredVendorData = [];
        foreach ($vendorDataContainer as $date => $vendorData) {
            if ($dateTimeToday === $date) {
                $vendorData['member'] = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                 ->findOneBy(['slug' => $vendorData['slug']]);
                $featuredVendorData = $vendorData;
                break;
            }
        }

        return $featuredVendorData;
    }

}
