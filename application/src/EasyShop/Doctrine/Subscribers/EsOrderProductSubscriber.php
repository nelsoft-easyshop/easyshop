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
                $invoiceNo = $entity->getOrder()->getInvoiceNo();
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::TRANSACTION_UPDATE);
                $phraseArray = $this->languageLoader
                                    ->getLine($activityType->getActivityPhrase());

                $unparsedPhrase = "";
                if(isset($this->changeSet['status'])){
                    if($status === EsOrderProductStatus::CASH_ON_DELIVERY){
                        $member = $entity->getSeller();
                        $unparsedPhrase = $phraseArray['completed'];
                    }
                    elseif($status === EsOrderProductStatus::RETURNED_BUYER){
                        $member = $entity->getSeller();
                        $unparsedPhrase = $phraseArray['item_cancel'];
                    }
                    elseif($status === EsOrderProductStatus::FORWARD_SELLER){
                        $member = $entity->getOrder()->getBuyer();
                        $unparsedPhrase = $phraseArray['item_received'];
                    }
                }
                elseif(isset($this->changeSet['isReject'])){
                    $member = $entity->getOrder()->getBuyer();
                    if($isReject === EsOrderProductStatus::IS_REJECT_NOT_ACTIVE){
                        $unparsedPhrase = $phraseArray['item_unreject'];
                    }
                    else{
                        $unparsedPhrase = $phraseArray['item_reject'];
                    }
                }

                if($unparsedPhrase !== ""){
                    $phrase = $this->activityManager
                                   ->constructActivityPhrase(['invoiceNo' => $invoiceNo],
                                                             $unparsedPhrase,
                                                             'EsOrder');

                    if($phrase !== ""){
                        $em->getRepository('EasyShop\Entities\EsActivityHistory')
                           ->createAcitivityLog($activityType, $phrase, $member);
                    }
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

