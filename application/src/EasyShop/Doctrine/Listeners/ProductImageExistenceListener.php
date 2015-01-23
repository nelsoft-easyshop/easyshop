<?php

namespace EasyShop\Doctrine\Listeners;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use EasyShop\Entities\EsProductImage;
 
class ProductImageExistenceListener 
{
 
    /**
     * Application environment
     *
     * @var string
     */
    private $environment;

 
    public function __construct($environment)
    {
        $this->environment = $environment;
    }
 
    /**
     * Post load event to check if the image exists in the appropriate asset directory
     *
     * @param LifecycleEventArgs $event
     */
    public function postLoad(LifecycleEventArgs $event)
    {
        $em = $event->getEntityManager();
        $entity = $event->getEntity();
        if ( ! $entity instanceOf EsProductImage ) { 
            return; 
        }
        
        $productImagePath = $entity->getProductImagePath();        
        if(trim($productImagePath) === ''){
            $entity->setDirectory(EsProductImage::DEFAULT_IMAGE_DIRECTORY);
            $entity->setFilename(EsProductImage::DEFAULT_IMAGE_FILE);
        }
        else{
            if(strtolower($this->environment) ===  "development" && !file_exists($productImagePath)){
                $entity->setDirectory(EsProductImage::IMAGE_UNAVAILABLE_DIRECTORY);
                $entity->setFilename(EsProductImage::IMAGE_UNAVAILABLE_FILE);
            }
            else{
                $reversedPath = strrev($productImagePath);
                $entity->setDirectory(substr($productImagePath,0,strlen($reversedPath)-strpos($reversedPath,'/')));
                $entity->setFilename(substr($productImagePath,strlen($reversedPath)-strpos($reversedPath,'/'),strlen($reversedPath)));
            }

        }                
    }
 
}

