<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsMember as EsMember;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsMemberListener implements EventSubscriber
{
    protected $activityHistory = [];

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
        $changeSet = $uow->getEntityChangeSet($entity);
        if ( !$entity instanceOf EsMember ) {
            return;
        }
    }

    /**
     * The postUpdate event occurs after the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function postUpdate(LifecycleEventArgs $event)
    {
        if(!empty($this->activityHistory)) {
            $em = $event->getEntityManager();
            foreach ($this->activityHistory as $activity) {
                $em->persist($activity);
            }
            $this->activityHistory = [];
            $em->flush();
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