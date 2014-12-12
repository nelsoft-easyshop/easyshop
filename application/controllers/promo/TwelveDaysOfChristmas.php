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
            '2014-12-14' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'seller1.jpg',
                'productImageUrl' => 'item1.jpg'
            ],
            '2014-12-15' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'seller1.jpg',
                'productImageUrl' => 'item2.jpg'
            ],
            '2014-12-16' => [
                'slug' => 'barbieforever',
                'vendorImageUrl' => 'seller1.jpg',
                'productImageUrl' => 'item3.jpg'
            ],
            '2014-12-17' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'seller2.jpg',
                'productImageUrl' => 'item4.jpg'
            ],
            '2014-12-18' => [
                'slug' => 'sansoncellshop',
                'vendorImageUrl' => 'seller3.jpg',
                'productImageUrl' => 'item5.jpg'
            ],
            '2014-12-19' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'seller4.jpg',
                'productImageUrl' => 'item6.jpg'
            ],
            '2014-12-20' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'seller4.jpg',
                'productImageUrl' => 'item7.jpg'
            ],
            '2014-12-21' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'seller4.jpg',
                'productImageUrl' => 'item8.jpg'
            ],
            '2014-12-22' => [
                'slug' => '05272014',
                'vendorImageUrl' => 'seller4.jpg',
                'productImageUrl' => 'item9.jpg'
            ],
            '2014-12-23' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'seller2.jpg',
                'productImageUrl' => 'item10.jpg'
            ],
            '2014-12-24' => [
                'slug' => 'airbornetechnologies',
                'vendorImageUrl' => 'seller2.jpg',
                'productImageUrl' => 'item11.jpg'
            ],
            '2014-12-25' => [
                'slug' => 'sansoncellshop',
                'vendorImageUrl' => 'seller3.jpg',
                'productImageUrl' => 'item12.jpg'
            ],
        ];
        $vendorOfTheDay = [];
        if (isset($vendorDataContainer[$dateTimeToday])) {
            $vendorOfTheDay = $vendorDataContainer[$dateTimeToday];
            $vendorOfTheDay['member'] = $this->em->getRepository('EasyShop\Entities\EsMember')
                                                 ->findOneBy(['slug' => $vendorOfTheDay['slug']]);
        }

        return $vendorOfTheDay;
    }
}
