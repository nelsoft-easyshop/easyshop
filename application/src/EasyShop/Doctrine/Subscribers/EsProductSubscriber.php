<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsProductSubscriber implements EventSubscriber
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
        if (!$entity instanceof EsProduct) {
            return;
        }

        if ($event->hasChangedField('lastmodifieddate')) {
            $this->changeSet['lastmodifieddate'] = $entity->getLastmodifieddate();
        }
        
        if ($event->hasChangedField('isDelete')) {
            $this->changeSet['isDelete'] = $entity->getIsDelete();
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
        if ($entity instanceof EsProduct) {
            if (count($this->changeSet) > 0) {
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::PRODUCT_UPDATE);
                $jsonString = "";
                $actionType = null;
                if ((int)$entity->getIsDelete() === (int)EsProduct::FULL_DELETE) {
                    $actionType = \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_FULL_DELETE;
                }
                else if ((int)$entity->getIsDelete() === (int)EsProduct::DELETE) {
                    $actionType = \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_SOFT_DELETE;
                }
                else if ((int)$entity->getIsDelete() === (int)EsProduct::ACTIVE &&
                        (int)$entity->getIsDraft() === (int)EsProduct::ACTIVE) {
                    $actionType = \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_UPDATE;
                    if (isset($this->changeSet['isDelete'])) {
                        $actionType = \EasyShop\Activity\ActivityTypeProductUpdate::ACTION_PRODUCT_RESTORE;
                    }
                }
                if ($actionType !== null) {
                    $data = [
                        'productId' => $entity->getIdProduct(),
                        'name' => $entity->getName(),
                        'price' => $entity->getPrice(),
                        'discount' => $entity->getDiscount(),
                    ];
                    $jsonString = \EasyShop\Activity\ActivityTypeProductUpdate::constructJSON($data, $actionType);
                    $em->getRepository('EasyShop\Entities\EsActivityHistory')
                        ->createAcitivityLog($activityType, $jsonString, $entity->getMember());
                }
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

