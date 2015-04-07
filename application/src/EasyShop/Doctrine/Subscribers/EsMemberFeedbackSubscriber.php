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
            $action = \EasyShop\Activity\ActivityTypeFeedbackUpdate::ACTION_FEEDBACK_USER;
            $data = [
                'revieweeId' => $forMember->getIdMember(),
                'message' => $entity->getFeedbMsg(),
                'rating1' => $entity->getRating1(),
                'rating2' => $entity->getRating2(),
                'rating3' => $entity->getRating3(),
            ];
            $jsonData = \EasyShop\Activity\ActivityTypeFeedbackUpdate::constructJSON($data, $action);
            $em->getRepository('EasyShop\Entities\EsActivityHistory')
               ->createAcitivityLog($activityType, $jsonData, $member);
       
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

