<?php

namespace EasyShop\Activity;

class ActivityTypeProductUpdate extends AbstractActivityType
{
    /**
     * Product Manager
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * Constructor
     *
     * @param EasyShop\Product\ProductManager $productManager
     *
     */
    public function __construct($productManager)
    {
        parent::__construct();
        $this->productManager  = $productManager;
    }

    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_PRODUCT_UPDATE = 0;
    
    /**
     * Action constant for product delete 
     *
     * @var integer
     */
    const ACTION_PRODUCT_SOFT_DELETE = 1;
    
    /**
     * Action constant for product update 
     *
     * @var integer
     */
    const ACTION_PRODUCT_FULL_DELETE = 2;
    
    /**
     * Action constant for product restore 
     *
     * @var integer
     */
    const ACTION_PRODUCT_RESTORE = 4;

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

        if(isset($activityData->productId)){
            $product = $this->productManager->getProductDetails($activityData->productId);
            $formattedData['name'] = $product->getName();
            $formattedData['slug'] = $product->getSlug();
            $formattedData['productId'] = $activityData->productId;
            $productImage = $product->getDefaultImage();
            $formattedData['imageDirectory'] = $productImage->getDirectory();
            $formattedData['imageFile'] = $productImage->getFilename();
            $formattedData['action'] = $activityData->action;
        }

        return $formattedData;
    }
}


