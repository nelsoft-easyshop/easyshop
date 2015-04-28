<?php

namespace EasyShop\Activity;

class ActivityTypeVendorSubscription extends AbstractActivityType
{    
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $entityManager;
    
    /**
     * Product Manager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * User Manager
     *
     * @var EasyShop\User\UserManager
     */
    private $userManager;

    /**
     * Constructor
     *
     * @param Doctrine\ORM\EntityManager $entityManager
     * @param EasyShop\User\UserManager $userManager
     * @param EasyShop\Product\ProductManager $productManager
     */
    public function __construct($entityManager, $userManager, $productManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->userManager = $userManager;
        $this->productManager = $productManager;
    }

    /**
     * Action constant for unfollow
     *
     * @var integer
     */
    const ACTION_UNFOLLOW = 0;

    /**
     * Action constant for follow
     *
     * @var integer
     */
    const ACTION_FOLLOW = 1;
    
    /**
     * Return formatted data for specific activity
     *
     * @param string $jsonData
     * @return mixed
     */
    public function getFormattedData($jsonData)
    {
        $formattedData = [];
        $activityData = json_decode($jsonData);

        if(isset($activityData->vendor) && isset($activityData->action)){
            if($activityData->action === self::ACTION_UNFOLLOW){
                $formattedData['stringAction'] = "Unfollowed";
            }
            elseif($activityData->action === self::ACTION_FOLLOW){
                $formattedData['stringAction'] = "Followed";
            }
            $member = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                          ->find($activityData->vendor);
            if($member){
                $formattedData['storeName'] = $member->getStoreName();
                $formattedData['slug'] = $member->getSlug();
                $formattedData['userImage'] = $this->userManager
                                                   ->getUserImage($activityData->vendor, 'small');
            }
            $formattedData['action'] = $activityData->action;
        }

        return $formattedData;
    }
    
    
    
}


