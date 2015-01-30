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

}