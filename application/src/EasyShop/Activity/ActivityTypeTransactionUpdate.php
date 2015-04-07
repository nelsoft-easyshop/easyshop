<?php

namespace EasyShop\Activity;

class ActivityTypeTransactionUpdate extends AbstractActivityType
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
     * Constructor
     *
     */
    public function __construct($entityManager, $productManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->productManager = $productManager; 
    }

    /**
     * Action constant for new purchase 
     *
     * @var integer
     */
    const ACTION_BOUGHT = 1;

    /**
     * Action constant for transaction received 
     *
     * @var integer
     */
    const ACTION_RECEIVED = 1;
    
    /**
     * Action constant for transaction refunded 
     *
     * @var integer
     */
    const ACTION_REFUNDED = 2;
    
    /**
     * Action constant for transaction completed 
     *
     * @var integer
     */
    const ACTION_COD_COMPLETED = 3;
    
    /**
     * Action constant for transaction rejected 
     *
     * @var integer
     */
    const ACTION_REJECTED = 4;
    
       
    /**
     * Action constant for transaction unrejected 
     *
     * @var integer
     */
    const ACTION_UNREJECTED = 5;

           
    /**
     * Action constant for add shipment detail
     *
     * @var integer
     */
    const ACTION_ADD_SHIPMENT = 6;
    
    /**
     * Action constant for edit shipment detail
     *
     * @var integer
     */
    const ACTION_EDIT_SHIPMENT = 7;

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

        if(isset($activityData->orderProductId)){
            $orderProduct = $this->entityManager->getRepository('EasyShop\Entities\EsOrderProduct')
                                 ->find($activityData->orderProductId);
            $order = $orderProduct->getOrder();
            $product = $this->productManager->getProductDetails($orderProduct->getProduct());
            $formattedData['invoiceNumber'] = $order->getInvoiceNo();            
            $formattedData['name'] = $product->getName();
            $formattedData['slug'] = $product->getSlug();
            $formattedData['productId'] = $product->getIdProduct();
            $productImage = $product->getDefaultImage();
            $formattedData['imageDirectory'] = $productImage->getDirectory();
            $formattedData['imageFile'] = $productImage->getFilename();
            $formattedData['action'] = $activityData->action;
        }

        return $formattedData;
    }
    
}


