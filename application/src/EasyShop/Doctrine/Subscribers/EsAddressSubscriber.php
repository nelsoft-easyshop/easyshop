<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsAddressSubscriber implements EventSubscriber
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

        if ( ! $entity instanceOf EsAddress ) {
            return;
        }

        if ((int)$entity->getStateregion()->getIdLocation() !== 0) {
            $addressKey = "stateregion";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_stateregion";
            }
            $this->changeSet[$addressKey] = $entity->getStateregion()->getLocation();
        }

        if ((int)$entity->getCity()->getIdLocation() !== 0) {
            $addressKey = "city";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_city";
            }
            $this->changeSet[$addressKey] = $entity->getCity()->getLocation();
        }

        if ((int)$entity->getCountry()->getIdLocation() !== 0) {
            $addressKey = "country";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_country";
            }

            $this->changeSet[$addressKey] = $entity->getCountry()->getLocation();
        }

        if ((string)$entity->getAddress() !== "") {
            $addressKey = "address";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_address";
            }
            $this->changeSet[$addressKey] = $entity->getAddress();
        }

        if ((string)$entity->getTelephone() !== "") {
            $addressKey = "telephone";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_telephone";
            }
            $this->changeSet[$addressKey] = $entity->getTelephone();
        }

        if ((string)$entity->getMobile() !== "") {
            $addressKey = "mobile";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_mobile";
            }
            $this->changeSet[$addressKey] = $entity->getMobile();
        }

        if ((string)$entity->getConsignee() !== "") {
            $addressKey = "consignee";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_consignee";
            }
            $this->changeSet[$addressKey] = $entity->getConsignee();
        }

        if ((int)$entity->getLat() !== 0) {
            $addressKey = "lat";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_lat";
            }
            $this->changeSet[$addressKey] = $entity->getLat();
        }

        if ((int)$entity->getLat() !== 0) {
            $addressKey = "lng";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_lng";
            }
            $this->changeSet[$addressKey] = $entity->getLng();
        }

        $this->saveActivity($event);
    }

    /**
     * The preUpdate event occurs before the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        if ( !$entity instanceOf EsAddress) {
            return;
        }

        if ($event->hasChangedField('stateregion')) {
            $addressKey = "stateregion";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_stateregion";
            }
            $this->changeSet[$addressKey] = $entity->getStateregion()->getLocation();
        }

        if ($event->hasChangedField('city')) {
            $addressKey = "city";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_city";
            }
            $this->changeSet[$addressKey] = $entity->getCity()->getLocation();
        }

        if ($event->hasChangedField('country')) {
            $addressKey = "country";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_country";
            }

            $this->changeSet[$addressKey] = $entity->getCountry()->getLocation();
        }

        if ($event->hasChangedField('address')) {
            $addressKey = "address";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_address";
            }
            $this->changeSet[$addressKey] = $entity->getAddress();
        }

        if ($event->hasChangedField('telephone')) {
            $addressKey = "telephone";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_telephone";
            }
            $this->changeSet[$addressKey] = $entity->getTelephone();
        }

        if ($event->hasChangedField('mobile')) {
            $addressKey = "mobile";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_mobile";
            }
            $this->changeSet[$addressKey] = $entity->getMobile();
        }

        if ($event->hasChangedField('consignee')) {
            $addressKey = "consignee";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_consignee";
            }
            $this->changeSet[$addressKey] = $entity->getConsignee();
        }

        if ($event->hasChangedField('lat') && (int)$entity->getLat() !== 0) {
            $addressKey = "lat";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_lat";
            }
            $this->changeSet[$addressKey] = $entity->getLat();
        }

        if ($event->hasChangedField('lng') && (int)$entity->getLat() !== 0) {
            $addressKey = "lng";
            if((int)$entity->getType() === EsAddress::TYPE_DELIVERY){
                $addressKey = "delivery_lng";
            }
            $this->changeSet[$addressKey] = $entity->getLng();
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
        if ( $entity instanceOf EsAddress) {
            if(count($this->changeSet) > 0){
                $action = \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_INFORMATION_UPDATE;
                $member = $em->getRepository('EasyShop\Entities\EsMember')
                             ->find($entity->getIdMember()->getIdMember());   
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::INFORMATION_UPDATE);
                $jsonString = \EasyShop\Activity\ActivityTypeInformationUpdate::constructJSON($this->changeSet, $action);      
                if($jsonString){
                    $em->getRepository('EasyShop\Entities\EsActivityHistory')
                       ->createAcitivityLog($activityType, $jsonString, $member);
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
            Events::postPersist,
        ];
    }
}

