<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsCat as EsCat;
use EasyShop\Entities\EsProduct as EsProduct;
use EasyShop\Entities\EsProductImage as EsProductImage;
use EasyShop\Entities\EsStyle as EsStyle;
use EasyShop\Entities\EsBrand as EsBrand;
use EasyShop\Entities\EsOptionalAttrhead as EsOptionalAttrhead;
use EasyShop\Entities\EsOptionalAttrdetail as EsOptionalAttrdetail;
use EasyShop\Entities\EsProductItem as EsProductItem;
use EasyShop\Entities\EsProductShippingHead as EsProductShippingHead;
use EasyShop\Entities\EsProductShippingDetail as EsProductShippingDetail;
use EasyShop\Entities\EsBillingInfo as EsBillingInfo;

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
     * Language Loader
     *
     * @var EasyShop\LanguageLoader\LanguageLoader
     */
    private $languageLoader;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em,
                                $productManager,
                                $stringUtility,
                                $languageLoader)
    {
        $this->em = $em;
        $this->productManager = $productManager;
        $this->stringUtility = $stringUtility;
        $this->languageLoader = $languageLoader;
    }

    /**
     * inserts new product in es_product table
     * @param  string   $productName
     * @param  string   $condition  
     * @param  string   $description 
     * @param  integer  $categoryId 
     * @param  integer  $memberId   
     * @param  float    $productPrice  
     * @param  float    $discount   
     * @param  boolean  $isCod      
     * @param  boolean  $isMeetUp      
     * @param  string   $customCategory
     * @param  integer  $brandId    
     * @param  string   $customBrand 
     * @param  integer  $styleId    
     * @param  string   $sku        
     * @param  string   $shortBrief 
     * @param  string   $keywords  
     * @return EasyShop\Entities\EsProduct
     */
    public function createProduct($productName,
                                  $condition,
                                  $description,
                                  $categoryId,
                                  $memberId,
                                  $productPrice,
                                  $discount = 0,
                                  $isCod = false,
                                  $isMeetUp = false,
                                  $isDraft = EsProduct::DRAFT,
                                  $billingInfoId = EsBillingInfo::DEFAULT_BILLING_ID,
                                  $customCategory = "",
                                  $brandId = EsBrand::CUSTOM_CATEGORY_ID,
                                  $customBrand = "",
                                  $styleId = EsStyle::DEFAULT_STYLE_ID,
                                  $sku = "",
                                  $shortBrief = "",
                                  $keywords = "")
    {
        $currentDateTime = date_create(date("Y-m-d H:i:s"));

        $productName = $this->stringUtility->removeNonUTF($productName);
        $condition = $this->stringUtility->removeNonUTF($condition);
        $customCategory = $this->stringUtility->removeNonUTF($customCategory);
        $customBrand = $this->stringUtility->removeNonUTF($customBrand);
        $keywords = $this->stringUtility->removeNonUTF($keywords);
        $sku = $this->stringUtility->removeNonUTF($sku);
        $shortBrief = $this->stringUtility->removeNonUTF($shortBrief);

        $description = substr($description, 0, 65000);
        $productSlug = $this->productManager->generateSlug($productName);

        $newProduct = new EsProduct();
        $newProduct->setName($productName)
                   ->setSku($sku)
                   ->setBrief($shortBrief)
                   ->setDescription($description)
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
                   ->setIsDraft($isDraft)
                   ->setIsCod($isCod)
                   ->setIsMeetup($isMeetUp)
                   ->setCreateddate($currentDateTime)
                   ->setLastmodifieddate($currentDateTime)
                   ->setStartdate($currentDateTime)
                   ->setEnddate($currentDateTime)
                   ->setSlug($productSlug);

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

    /**
     * insert new product image
     * @param string  $imagePath 
     * @param string  $fileType 
     * @param mixed   $product
     * @param boolean $isPrimary
     */
    public function addProductImage($imagePath, $fileType, $product, $isPrimary = false)
    {
        $newImage = new EsProductImage();
        $newImage->setProductImagePath($imagePath)
                 ->setProductImageType($fileType) 
                 ->setProduct($product)
                 ->setIsPrimary($isPrimary);

        $this->em->persist($newImage);
        $this->em->flush();

        return $newImage;
    }

    /**
     * Add attribute details for product
     * @param string  $attrValue 
     * @param float   $price 
     * @param string  $attrHead 
     * @param integer $imageId 
     */
    public function addProductAttributeDetail($attrValue, $price, $attrHead, $imageId = 0)
    {
        $attrValue = strlen($attrValue) > 0 ? $attrValue : "No Value";
        $attrDetail = new EsOptionalAttrdetail();
        $attrDetail->setValueName($attrValue)
                   ->setValuePrice($price)
                   ->setProductImgId($imageId)
                   ->setHead($attrHead);
        $this->em->persist($attrDetail);
        $this->em->flush();

        return $attrDetail;
    }

    /**
     * Add attribute head value
     * @param string $headVal 
     * @param mixed  $product 
     */
    public function addProductAttribute($headVal, $product)
    {
        $headVal = strlen($headVal) > 0 ? $headVal : "No Value";
        $attrHead = new EsOptionalAttrhead();
        $attrHead->setFieldName($headVal)
                 ->setProduct($product);
        $this->em->persist($attrHead);
        $this->em->flush();

        return $attrHead;
    }

    /**
     * add data in es_product_item
     * @param mixed   $product 
     * @param integer $quantity
     */
    public function addNewCombination($product, $quantity)
    {
        $quantity = $quantity > 0 ? $quantity : 1;
        $itemCombination = new EsProductItem();
        $itemCombination->setQuantity($quantity)
                        ->setProduct($product);
        $this->em->persist($itemCombination);
        $this->em->flush();

        return $itemCombination;
    }

    /**
     * Add product shipping info
     * @param mixed   $product
     * @param integer $itemId 
     * @param integer $locationId 
     * @param integer $price 
     */
    public function addShippingInfo($product, $itemId, $locationId, $price = 0)
    { 
        $shippingHead = new EsProductShippingHead();
        $shippingHead->setLocation($this->em->find("EasyShop\Entities\EsLocationLookup", $locationId));
        $shippingHead->setPrice($price);
        $shippingHead->setProduct($product);
        $this->em->persist($shippingHead);
 

        $shippingDetails = new EsProductShippingDetail();
        $shippingDetails->setShipping($shippingHead);
        $shippingDetails->setProductItem($this->em->find("EasyShop\Entities\EsProductItem", $itemId));
        $this->em->persist($shippingDetails);

        $this->em->flush();
    }

    /**
     * Validate input data for product upload
     * @param  mixed $data
     * @return mixed
     */
    public function validateUploadRequest($data)
    { 
        $returnArray = [
            'isSuccess' => false,
            'message' => '',
        ];

        $productName = isset($data['productName']) ? $data['productName'] : "";
        $productPrice = isset($data['productPrice']) ? $data['productPrice'] : 0;
        $condition = isset($data['condition']) ? $data['condition'] : "";
        $productDescription = isset($data['productDescription']) ? $data['productDescription'] : 0;
        $images = isset($data['images']) ? $data['images'] : [];
        $pictureToRemove = isset($data['pictureToRemove']) ? $data['pictureToRemove'] : []; 
        $combination = isset($data['combination']) ? $data['combination'] : [];
        $categoryId = isset($data['category']) ? $data['category'] : null;

        if(strlen($productName) <= 0 
            || strlen($productPrice) <= 0 
            || (float) $productPrice <= 0
            || strlen($productPrice) <= 0
            || strlen($productDescription) <= 0
            || strlen($condition) <= 0){

            $returnArray['message'] = "Fill (*) All Required Fields Properly!";
            return $returnArray;
        } 

        if($categoryId !== null && $categoryId !== EsCat::ROOT_CATEGORY_ID){
            $category = $this->em->find("EasyShop\Entities\EsCat", $categoryId);
            if($category === false){
                $returnArray['message'] = "Please select valid category!"; 
                return $returnArray;
            }
        }
        else{
            $returnArray['message'] = "Please select valid category!"; 
            return $returnArray;
        }
        

        if(strlen($productName) < EsProduct::MINIMUM_PRODUCT_NAME_LEN){
            $returnArray['message'] = "Product name must be atleast ".EsProduct::MINIMUM_PRODUCT_NAME_LEN." characters!";
            return $returnArray;
        }

        if(in_array($condition, $this->languageLoader->getLine('product_condition')) === false){
            $returnArray['message'] = "Invalid condition!";
            return $returnArray;
        }

        if(empty($combination) === false){
            $currentCombination = []; 
            foreach ($combination as $value) {
                $currentCombination[] = implode("", array_map('strtolower', $value['data'])); 
            }

            if(count($currentCombination) !== count(array_unique($currentCombination))){ 
                $returnArray['message'] = "Same combination is not allowed!";
                return $returnArray;
            }
        }

        foreach($images as $key => $value ) { 
            if (in_array($key, $pictureToRemove) || trim($images[$key]) === "") {
                unset($images[$key]);
            } 
        }

        if(empty($images)){
            $returnArray['message'] = "Please select at least one photo for your listing.";
            return $returnArray;
        }

        $returnArray['isSuccess'] = true;
        
        return $returnArray;
    }

}

