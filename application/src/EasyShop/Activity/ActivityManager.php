<?php

namespace EasyShop\Activity;

class ActivityManager
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * User Manager
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;
    
    /**
     * Product Manager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;
    
    /**
     * Pimple Container
     *
     * @var \Pimple\Container
     */
    private $container;
    

    /**
     * Constructor
     *
     * @param EasyShop\Product\ProductManager $productManager
     * @param EasyShop\User\UserManager $userManager
     * @param Doctrine\ORM\EntityManager $entityManager
     */
    public function __construct($productManager, $userManager, $entityManager)
    {
        $this->entityManager = $entityManager;

        $container = new \Pimple\Container();
        $container[\EasyShop\Entities\EsActivityType::INFORMATION_UPDATE] = function ($c) use ($userManager){
            return new ActivityTypeInformationUpdate($userManager);
        };
        $container[\EasyShop\Entities\EsActivityType::PRODUCT_UPDATE] = function ($c) use ($productManager){
            return new ActivityTypeProductUpdate($productManager);
        };
        $container[\EasyShop\Entities\EsActivityType::TRANSACTION_UPDATE] = function ($c) use ($productManager, $entityManager) {
            return new ActivityTypeTransactionUpdate($entityManager, $productManager);
        };
        $container[\EasyShop\Entities\EsActivityType::FEEDBACK_UPDATE] = function ($c) use ($entityManager, $userManager, $productManager){
            return new ActivityTypeFeedbackUpdate($entityManager, $userManager, $productManager);
        };
        $container[\EasyShop\Entities\EsActivityType::VENDOR_SUBSCRIPTION] = function ($c) use ($entityManager, $userManager, $productManager){
            return new ActivityTypeVendorSubscription($entityManager, $userManager, $productManager);
        };

        $this->container = $container;
    }

    /**
     * Get user activities
     *
     * @param integer $memberId
     * @param integer $perPage
     * @param integer $offset
     * @return mixed
     */
    public function getUserActivities($memberId, $perPage, $offset, $fromDate, $toDate, $sortAscending)
    {
        $activities = $this->entityManager
                           ->getRepository('EasyShop\Entities\EsActivityHistory')
                           ->getActivities($memberId, $perPage, $offset, $fromDate, $toDate, $sortAscending);
        $formattedActivityData = [];
        foreach($activities as $activity){
            $activityTypeId = $activity->getActivityType()->getIdActivityType();
            $activityClass = null;
            if(isset($this->container[$activityTypeId])){
                $activityClass = $this->container[$activityTypeId];
            }
            
            if($activityClass !== null){
                $formattedActivityData[] = [
                    'type' => $activityTypeId,
                    'data' => $activityClass->getFormattedData($activity->getJsonData()),
                    'activityDate' => $activity->getActivityDatetime()->format('d M Y'),
                    'activityTime' => $activity->getActivityDatetime()->format('h:i a'),
                ];
            }  
        }

        return $formattedActivityData;
    }

    /**
     * Count activity per user
     * @param  integer $memberId
     * @return integer
     */
    public function getTotalActivityCount($memberId, $fromDate, $toDate)
    {
        $activityCount = $this->entityManager
                              ->getRepository('EasyShop\Entities\EsActivityHistory')
                              ->countActivityCount($memberId, $fromDate, $toDate);

        return $activityCount;
    }
    
    /**
     * Get Activity Instance
     *
     * @param integer $activityType
     * @return EasyShop\Activity\AbstractActivityType
     */
    public function getActivityInstance($activityType)
    {
        $activityInstance = null;
        if(isset($this->container[$activityType])){
            $activityInstance = $this->container[$activityType];
        }
        
        return $activityInstance;
    }
    
}

