<?php

namespace EasyShop\Activity;

class ActivityTypeFeedbackUpdate extends AbstractActivityType
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
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_USER = 0;

    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_PRODUCT = 1;
    
    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_FEEDBACK_PRODUCT_REPLY = 2;
    
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

        if($activityData->action === self::ACTION_FEEDBACK_USER){
            $reviewee = $this->entityManager->getRepository('EasyShop\Entities\EsMember')
                                ->find($activityData->revieweeId);
            $formattedData['revieweeName'] = $reviewee->getStorename();            
            $formattedData['revieweeSlug'] = $reviewee->getSlug();
            $formattedData['revieweeImage']  = $this->userManager
                                                    ->getUserImage($activityData->revieweeId, 'small');
            $formattedData['message'] = $activityData->message;
        }
        else if($activityData->action === self::ACTION_FEEDBACK_PRODUCT || $activityData->action === self::ACTION_FEEDBACK_PRODUCT_REPLY ){
            
            $product = $this->productManager->getProductDetails($activityData->productId);
            $formattedData['name'] = $product->getName();
            $formattedData['slug'] = $product->getSlug();
            $formattedData['productId'] = $activityData->productId;
            $productImage = $product->getDefaultImage();
            $formattedData['imageDirectory'] = $productImage->getDirectory();
            $formattedData['imageFile'] = $productImage->getFilename();
            $formattedData['message'] = $activityData->message;
        }
        $formattedData['action'] = $activityData->action;
     

        return $formattedData;
    }
    
    
    
}


