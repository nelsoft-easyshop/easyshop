<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsMember as EsMember; 
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsMemberSubscriber implements EventSubscriber
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
        if ( !$entity instanceOf EsMember) {
            return;
        }

        if ($event->hasChangedField('storeName')) {
            $this->changeSet['storeName'] = $entity->getStoreName();
        }

        if ($event->hasChangedField('password')) {
            $this->changeSet['password'] = $entity->getPassword();
        }

        if ($event->hasChangedField('contactno')) {
            $this->changeSet['contactno'] = $entity->getContactno();
        }

        if ($event->hasChangedField('isEmailVerify')) {
            $this->changeSet['isEmailVerify'] = $entity->getIsEmailVerify() ? "Verified" : "Unverified";
        }

        if ($event->hasChangedField('gender')) {
            $this->changeSet['gender'] = $entity->getGender() === "M" ? "Male" : "Female";
        }

        if ($event->hasChangedField('email')) {
            $this->changeSet['email'] = $entity->getEmail();
        }

        if ($event->hasChangedField('birthday')) {
            $this->changeSet['birthday'] = $entity->getBirthday()->format('Y-m-d');
        }

        if ($event->hasChangedField('fullname')) {
            $this->changeSet['fullname'] = $entity->getFullname();
        }

        if ($event->hasChangedField('storeDesc')) {
            $this->changeSet['storeDesc'] = $entity->getStoreDesc();
        }

        if ($event->hasChangedField('slug')) {
            $this->changeSet['slug'] = $entity->getSlug();
        }

        if ($event->hasChangedField('website')) {
            $this->changeSet['website'] = $entity->getWebsite();
        }

        if ($event->hasChangedField('lastBannerChanged')) {
            $this->changeSet['lastBannerChanged'] = $entity->getLastBannerChanged();
        }

        if ($event->hasChangedField('lastAvatarChanged')) {
            $this->changeSet['lastAvatarChanged'] = $entity->getLastAvatarChanged();
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
        if ( $entity instanceOf EsMember) {
            if(count($this->changeSet) > 0){
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::INFORMATION_UPDATE);
                if(isset($this->changeSet['lastAvatarChanged'])){
                    $this->changeSet['memberId'] = $entity->getIdMember();
                    $action = \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_AVATAR_UPDATE;
                }
                elseif(isset($this->changeSet['lastBannerChanged'])){
                    $this->changeSet['memberId'] = $entity->getIdMember();
                    $action = \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_BANNER_UPDATE;
                }
                else{
                    $action = \EasyShop\Activity\ActivityTypeInformationUpdate::ACTION_INFORMATION_UPDATE;
                }
                $jsonString = \EasyShop\Activity\ActivityTypeInformationUpdate::constructJSON($this->changeSet, $action);
                if($jsonString !== ""){
                    $em->getRepository('EasyShop\Entities\EsActivityHistory')
                       ->createAcitivityLog($activityType, $jsonString, $entity);
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

