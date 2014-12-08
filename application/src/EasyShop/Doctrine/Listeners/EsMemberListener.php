<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsMember as EsMember; 
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsMemberListener implements EventSubscriber
{
    protected $changeSet = [];

    /**
     * Activity Manager Instance
     *
     * @var Easyshop\Activity\ActivityManager
     */
    private $activityManager;

    /**
     * Constructor.
     * 
     */
    public function __construct($activityManager)
    {
        $this->activityManager = $activityManager;
    }

    /**
    * The postPersist event occurs for an entity after the entity has been made persistent.
    *
    * @param LifecycleEventArgs $event
    */
    public function postPersist(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();

        if ( ! $entity instanceOf EsMember ) {
            return;
        }
    }

    /**
     * The preUpdate event occurs before the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $uow = $em->getUnitOfWork();
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
            $this->changeSet['gender'] = $entity->getGender() ? "Female" : "Male";
        }

        if ($event->hasChangedField('email')) {
            $this->changeSet['email'] = $entity->getEmail();
        }

        if ($event->hasChangedField('birthday')) {
            $this->changeSet['birthday'] = $entity->getBirthday();
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
    }

    /**
     * The postUpdate event occurs after the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        $phrase = "";
        if ( $entity instanceOf EsMember) {
            if(count($this->changeSet) > 0){
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::INFORMATION_UPDATE);
                $phrase = $this->activityManager
                               ->constructActivityPhrase($this->changeSet, 'update_information');
                $em->getRepository('EasyShop\Entities\EsActivityHistory')
                   ->createAcitivityLog($activityType, $phrase, $entity);
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