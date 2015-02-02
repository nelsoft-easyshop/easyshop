<?php

namespace EasyShop\Doctrine\Subscribers;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsProductSubscriber implements EventSubscriber
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
        if ( !$entity instanceOf EsProduct) {
            return;
        }

        if ($event->hasChangedField('lastmodifieddate')) {
            $this->changeSet['lastmodifieddate'] = $entity->getLastmodifieddate();
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
        if ( $entity instanceOf EsProduct) {
            if(count($this->changeSet) > 0){
                $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                                   ->find(EsActivityType::PRODUCT_UPDATE);
                $phraseArray = $this->languageLoader
                                    ->getLine($activityType->getActivityPhrase());

                $phraseValue = "";
                if ((int)$entity->getIsDelete() === (int)EsProduct::FULL_DELETE) {
                    $phraseValue = $phraseArray['trash'];
                }
                elseif ((int)$entity->getIsDelete() === (int)EsProduct::DELETE) {
                    $phraseValue = $phraseArray['delete'];
                }
                elseif ((int)$entity->getIsDelete() === (int)EsProduct::ACTIVE
                        && (int)$entity->getIsDraft() === (int)EsProduct::ACTIVE) { 
                    $phraseValue = $phraseArray['update'];
                }
                $phrase = $this->activityManager
                               ->constructActivityPhrase(['name' => $entity->getName()],
                                                         $phraseValue,
                                                         'EsProduct');
                if($phrase !== ""){
                    $em->getRepository('EasyShop\Entities\EsActivityHistory')
                       ->createAcitivityLog($activityType, $phrase, $entity->getMember());
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

