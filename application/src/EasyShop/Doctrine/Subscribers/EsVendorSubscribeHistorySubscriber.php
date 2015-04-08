<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsVendorSubscribeHistory as EsVendorSubscribeHistory;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsVendorSubscribeHistorySubscriber implements EventSubscriber
{
    protected $changeSet = [];

    /**
    * The postPersist event occurs for an entity after the entity has been made persistent.
    *
    * @param LifecycleEventArgs $event
    */
    public function postPersist(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();

        if ( ! $entity instanceOf EsVendorSubscribeHistory ) {
            return;
        }

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
        if ( $entity instanceOf EsVendorSubscribeHistory) {
            $member = $entity->getMember();
            $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                               ->find(EsActivityType::VENDOR_SUBSCRIPTION);

            if(strtoupper($entity->getAction()) === 'FOLLOW'){
                $actionType = \EasyShop\Activity\ActivityTypeVendorSubscription::ACTION_FOLLOW;
            }
            else{
                $actionType = \EasyShop\Activity\ActivityTypeVendorSubscription::ACTION_UNFOLLOW;
            }

            if($actionType !== null){
                $data = [
                    'vendor' => $entity->getVendor()->getIdMember(),
                ];
                $jsonData = \EasyShop\Activity\ActivityTypeVendorSubscription::constructJSON($data, $actionType);
                $em->getRepository('EasyShop\Entities\EsActivityHistory')
                   ->createAcitivityLog($activityType, $jsonData, $member);
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
            Events::postPersist,
        ];
    }
}

