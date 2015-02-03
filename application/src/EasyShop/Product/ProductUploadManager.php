<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsStyle as EsStyle;
use EasyShop\Entities\EsBrand as EsBrand;

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
     * String Utility
     *
     * @var EasyShop\Utility\StringUtility
     */
    private $stringUtility;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $productManager,
                                $stringUtility)
    {
        $this->em = $em;
        $this->productManager = $productManager;
        $this->stringUtility = $stringUtility;
    }

    /**
     * inserts new product in es_product table
     * @param  string   $productName
     * @param  string   $descriptions  
     * @param  integer  $categoryId 
     * @param  integer  $memberId   
     * @param  float    $productPrice  
     * @param  string   $condition  
     * @param  boolean  $isCod     
     * @param  boolean  $isEdit     
     * @param  float    $discount   
     * @param  string   $customCategory
     * @param  string   $customBrand
     * @param  string   $keywords   
     * @param  integer  $brandId    
     * @param  integer  $styleId    
     * @param  string   $sku        
     * @param  string   $shortBrief 
     * @return EasyShop\Entities\EsProduct
     */
    public function createProduct($productName,
                                  $descriptions,
                                  $categoryId,
                                  $memberId,
                                  $productPrice,
                                  $condition,
                                  $isCod = false,
                                  $isEdit = false,
                                  $discount = "0",
                                  $customCategory = "",
                                  $customBrand = "",
                                  $keywords = "",
                                  $brandId = EsBrand::CUSTOM_CATEGORY_ID,
                                  $styleId = EsStyle::DEFAULT_STYLE_ID,
                                  $sku = "",
                                  $shortBrief = "")
    {
        $currentDateTime = date_create(date("Y-m-d H:i:s"));

        $productName = $this->stringUtility->removeNonUTF($productName);
        $condition = $this->stringUtility->removeNonUTF($condition);
        $customCategory = $this->stringUtility->removeNonUTF($customCategory);
        $customBrand = $this->stringUtility->removeNonUTF($customBrand);
        $keywords = $this->stringUtility->removeNonUTF($keywords);
        $sku = $this->stringUtility->removeNonUTF($sku);
        $shortBrief = $this->stringUtility->removeNonUTF($shortBrief);

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