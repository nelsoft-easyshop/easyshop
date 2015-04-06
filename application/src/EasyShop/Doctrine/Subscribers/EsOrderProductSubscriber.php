<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsOrderProduct as EsOrderProduct;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsOrderProductSubscriber implements EventSubscriber
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
     * The preUpdate event occurs before the database update operations to entity data.
     * 
     * @param  LifecycleEventArgs $event
     */
    public function preUpdate(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        if ( !$entity instanceOf EsOrderProduct) {
            return;
        }

        if ($event->hasChangedField('status')) {
            $this->changeSet['status'] = $entity->getStatus();
        }

        if ($event->hasChangedField('isReject')) {
            $this->changeSet['isReject'] = $entity->getIsReject();
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
        if ( $entity instanceOf EsOrderProduct) {
            if(count($this->changeSet) > 0){
                $member = null;
                $status = (int)$entity->getStatus()->getIdOrderProductStatus();
                $isReject = (int)$entity->getIsReject();
                $orderProductId = $entity->getIdOrderProduct();
                $orderId = $entity->getOrder()->getIdOrder();
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::TRANSACTION_UPDATE);
                $jsonData = "";
                $action = null;
                if(isset($this->changeSet['status'])){
                    if($status === EsOrderProductStatus::CASH_ON_DELIVERY){
                        $member = $entity->getSeller();
                        $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_COD_COMPLETED;
                    }
                    elseif($status === EsOrderProductStatus::RETURNED_BUYER){
                        $member = $entity->getSeller();
                        $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_REFUNDED;
                    }
                    elseif($status === EsOrderProductStatus::FORWARD_SELLER){
                        $member = $entity->getOrder()->getBuyer();
                        $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_RECEIVED;
                    }
                }
                elseif(isset($this->changeSet['isReject'])){
                    $member = $entity->getOrder()->getBuyer();
                    if($isReject === EsOrderProductStatus::IS_REJECT_NOT_ACTIVE){
                        $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_UNREJECTED;
                    }
                    else{
                        $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_REJECTED;
                    }
                }

                if($action !== null && $member !== null){
                    $activity = new \EasyShop\Activity\ActivityTypeTransactionUpdate();  
                    $data = [
                        'orderId' => $orderId,
                        'orderProductId' => $orderProductId,
                    ];
                    $jsonString = $activity->constructJSON($data, $action);
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
        ];
    }
}

