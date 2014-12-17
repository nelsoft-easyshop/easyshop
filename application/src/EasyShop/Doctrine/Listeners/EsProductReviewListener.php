<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber as EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProductReview as EsProductReview;
use EasyShop\Entities\EsActivityType as EsActivityType;

class EsProductReviewListener implements EventSubscriber
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

        if ( ! $entity instanceOf EsProductReview ) {
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
        if ( $entity instanceOf EsProductReview) { 
            $product = $entity->getProduct();
            $member = $entity->getMember();
            $activityType = $em->getRepository('EasyShop\Entities\EsActivityType')
                               ->find(EsActivityType::FEEDBACK_UPDATE);
            $phraseArray = $this->languageLoader
                                ->getLine($activityType->getActivityPhrase());
            if((int)$entity->getPReviewid() != EsProductReview::PRODUCT_REVIEW_DEFAULT){
                $unparsedPhrase = $phraseArray['product']['reply'];
            }
            else{
                $unparsedPhrase = $phraseArray['product']['review'];
            }

            $phrase = $this->activityManager
                           ->constructActivityPhrase(['name' => $product->getName()],
                                                     $unparsedPhrase,
                                                     'EsProduct');
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

