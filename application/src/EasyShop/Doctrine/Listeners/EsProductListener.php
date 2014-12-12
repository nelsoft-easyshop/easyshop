<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProduct as EsProduct;

class EsProductListener implements EventSubscriber
{
    /**
     * Return all subscribed events for this class
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [];
    }
}