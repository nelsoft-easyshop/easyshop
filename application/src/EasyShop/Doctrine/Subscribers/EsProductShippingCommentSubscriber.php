<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProductShippingComment as EsProductShippingComment;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsProductShippingCommentSubscriber implements EventSubscriber
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

        if ( ! $entity instanceOf EsProductShippingComment ) {
            return;
        }

        $order = $entity->getOrderProduct()->getOrder();
        $this->changeSet['invoiceNo'] = $order->getInvoiceNo();
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
        if ( !$entity instanceOf EsProductShippingComment) {
            return;
        }

        $modifiedCount = 0;

        if ($event->hasChangedField('courier')) {
            $modifiedCount += 1;
        }

        if ($event->hasChangedField('trackingNum')) {
            $modifiedCount += 1;
        }

        if ($event->hasChangedField('comment')) {
            $modifiedCount += 1;
        }

        if ($event->hasChangedField('expectedDate')
            && $entity->getExpectedDate()->format('Y-m-d') !== "-0001-11-30") {
            $modifiedCount += 1;
        }

        if ($event->hasChangedField('deliveryDate')
            && $entity->getDeliveryDate()->format('Y-m-d') !== "-0001-11-30") {
            $modifiedCount += 1;
        }

        if($modifiedCount > 1){
            $order = $entity->getOrderProduct()->getOrder();
            $this->changeSet['invoiceNo'] = $order->getInvoiceNo();
            $this->changeSet['modified'] = true;
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
        if ( $entity instanceOf EsProductShippingComment) {
            if(count($this->changeSet) > 0){
                $member = $entity->getMember();
                $orderProduct = $entity->getOrderProduct();
                $orderProductId = $orderProduct->getIdOrderProduct();
                $orderId = $orderProduct->getOrder()->getIdOrder();
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::TRANSACTION_UPDATE);
                $activity = new \EasyShop\Activity\ActivityTypeTransactionUpdate();   
                if(isset($this->changeSet['modified'])){
                    $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_EDIT_SHIPMENT;
                    unset($this->changeSet['modified']);
                }
                else{
                    $action = \EasyShop\Activity\ActivityTypeTransactionUpdate::ACTION_ADD_SHIPMENT;
                }

                if($action !== null){
                    $jsonData = $activity->constructJSON($orderId, $orderProductId, $action);
                    $em->getRepository('EasyShop\Entities\EsActivityHistory')
                       ->createAcitivityLog($activityType, $jsonData, $member);
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

