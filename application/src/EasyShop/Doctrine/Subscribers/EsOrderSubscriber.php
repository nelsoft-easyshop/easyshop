<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsOrder as EsOrder;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsOrderSubscriber implements EventSubscriber
{
    protected $changeSet = [];

    /**
     * The preUpdate event occurs before the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        if ( !$entity instanceOf EsOrder) {
            return;
        }

        if ($event->hasChangedField('dateadded')) {
            $this->changeSet['dateadded'] = $entity->getDateadded();
        }
    }

    /**
     * The postUpdate event occurs after the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $this->saveActivity($event);
    }

    /**
     * Trigger save activity
     * @param  LifecycleEventArgs $event
     */
    private function saveActivity(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        $phrase = "";
        if ( $entity instanceOf EsOrder) {
            if(count($this->changeSet) > 0){
                $member = $entity->getBuyer();
                $orderId = $entity->getIdOrder();
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::TRANSACTION_UPDATE);
                $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_BOUGHT;
                $data = [
                    'orderId' => $orderId,
                ];
                $jsonString = \EasyShop\Activity\ActivityTypeTransactionUpdate::constructJSON($data, $action);
                $em->getRepository('EasyShop\Entities\EsActivityHistory')
                   ->createAcitivityLog($activityType, $jsonString, $member);

           }
        }
    }

    /**
     * Return all subscribed events for this class
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preUpdate,
            Events::postUpdate,
        ];
    }
}

