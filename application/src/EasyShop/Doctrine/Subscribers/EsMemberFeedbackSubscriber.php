<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsMemberFeedback as EsMemberFeedback;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsMemberFeedbackSubscriber implements EventSubscriber
{
    protected $changeSet = [];

    /**
     * Activity Manager Instance
     *
     * @var Easyshop\Activity\ActivityManager
     */
    private $activityManager;

    /**
     * Language Loader Instance
     *
     * @var Easyshop\LanguageLoader\LanguageLoader
     */
    private $languageLoader;

    /**
     * Constructor.
     * 
     */
    public function __construct($activityManager, $languageLoader)
    {
        $this->activityManager = $activityManager;
        $this->languageLoader = $languageLoader;
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

        if ( ! $entity instanceOf EsMemberFeedback ) {
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
        if ( $entity instanceOf EsMemberFeedback) { 
            $member = $entity->getMember();
            $forMember = $entity->getForMemberid();
            $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                               ->find(EsActivityType::FEEDBACK_UPDATE);
            $unparsedPhrase = $this->languageLoader
                                   ->getLine($activityType->getActivityPhrase());
 

            $phrase = $this->activityManager
                           ->constructActivityPhrase(['storeName' => $forMember->getStoreName()],
                                                     $unparsedPhrase['user'],
                                                     'EsMember');
            if($phrase !== ""){
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
            Events::postPersist,
        ];
    }
}

