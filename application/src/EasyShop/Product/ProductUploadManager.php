<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsStyle as EsStyle;

/**
 * Product Upload Manager Class
 *
 * @author Ryan Vasquez 
 */
class ProductUploadManager
{
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Entity Manager instance
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $productManager)
    {
        $this->em = $em;
        $this->productManager = $productManager;
    }

    /**
     * inserts new product in es_product table
     * @param  string   $productName
     * @param  string   $sku        
     * @param  string   $shortBrief 
     * @param  string   $descriptions  
     * @param  string   $keywords   
     * @param  integer  $brandId    
     * @param  integer  $categoryId 
     * @param  integer  $styleId    
     * @param  integer  $memberId   
     * @param  float    $productPrice  
     * @param  float    $discount   
     * @param  string   $condition  
     * @param  string   $customCategory
     * @param  string   $customBrand
     * @param  boolean  $isEdit     
     * @return EasyShop\Entities\EsProduct
     */
    public function createProduct($productName,
                                  $sku,
                                  $shortBrief,
                                  $descriptions,
                                  $keywords,
                                  $brandId,
                                  $categoryId,
                                  $styleId,
                                  $memberId,
                                  $productPrice,
                                  $discount,
                                  $condition,
                                  $customCategory,
                                  $customBrand,
                                  $isEdit = false)
    {
        $currentDateTime = date_create(date("Y-m-d H:i:s"));

        $newProduct = new EsProduct();
        $newProduct->setName($productName)
                   ->setSku($sku)
                   ->setBrief($shortBrief)
                   ->setDescription($descriptions)
                   ->setKeywords($keywords)
                   ->setBrand($this->em->find("EasyShop\Entities\EsBrand", $brandId))
                   ->setCat($this->em->find("EasyShop\Entities\EsCat", $categoryId))
                   ->setStyle($this->em->find("EasyShop\Entities\EsStyle", $styleId))
                   ->setMember($this->em->find("EasyShop\Entities\EsMember", $memberId))
                   ->setPrice($productPrice)
                   ->setDiscount($discount)
                   ->setCondition($condition)
                   ->setCatOtherName($customCategory)
                   ->setBrandOtherName($customBrand)
                   ->setIsDraft(EsProduct::DRAFT)
                   ->setCreateddate($currentDateTime)
                   ->setLastmodifieddate($currentDateTime)
                   ->setStartdate($currentDateTime)
                   ->setEnddate($currentDateTime); 

        try{
            $this->em->persist($newProduct);
            $this->em->flush();
        }
        catch(Exception $e){ 
            return false;
        }
        $this->productManager->generateSearchKeywords($newProduct->getIdProduct());

        return $newProduct;
    }

    /**
     * Generate token for upload process
     * @param  integer $memberId
     * @return string 
     */
    public function requestUploadToken($memberId)
    {
        $tempId = strtolower(substr( "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ,
                                     mt_rand( 0 ,50 ) ,1 ) .substr( md5( time() ), 1));

        $member = $this->em->find("EasyShop\Entities\EsMember", $memberId);
        $member->setTempId($tempId);
        $this->em->flush(); 

        return $tempId;
    }

}