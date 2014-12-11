<?php

class TwelveDaysOfChristmas extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->em = $this->serviceContainer['entity_manager'];
        $this->promoManager = $this->serviceContainer['promo_manager'];
    }

    public function getData()
    {
        $promoData = $this->promoManager->getActiveDataForTwelveDaysOfChristmasByDate(new DateTime('now'));

    }
}