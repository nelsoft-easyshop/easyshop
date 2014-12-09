<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsAddress as EsAddress;
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

        if ( ! $entity instanceOf EsAddress ) {
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
        if ( !$entity instanceOf EsAddress) {
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
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        $phrase = "";
        if ( $entity instanceOf EsAddress) {
            if(count($this->changeSet) > 0){
                $member = $em->getRepository('EasyShop\Entities\EsMember')
                             ->find($entity->getIdMember()->getIdMember());
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::INFORMATION_UPDATE);
                $phrase = $this->activityManager
                               ->constructActivityPhrase($this->changeSet,
                                                         'update_information',
                                                         'EsAddress');
                $em->getRepository('EasyShop\Entities\EsActivityHistory')
                   ->createAcitivityLog($activityType, $phrase, $member);
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