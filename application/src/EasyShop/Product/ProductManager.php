<?php

namespace EasyShop\Product;

use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsOrder; 
use EasyShop\Entities\EsProductShippingHead; 
use EasyShop\Entities\EsMemberProdcat;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product Manager Class
 *
 * @author Ryan Vasquez
 * @author stephenjanz
 */
class ProductManager
{
    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    /**
     * Applies discount to a product
     * @param  array  $products [description]
     * @return [type]           [description]
     */
    public function getDiscountedPrice($product = array(),$memberId)
    { 
        $CI = get_instance(); 
        $this->promoManager = $CI->kernel->serviceContainer['promo_manager'];

        foreach ($product as $key => $value) {
            $buyerId = $memberId;
            $productId =$value['idProduct'];
            $isPromote =  $value['isPromote'];
            $price =  $value['price'];  
            $startDate = $value['startdate']; 
            $endDate = $value['enddate'];
            $promoType = $value['promoType'];
            $discount = $value['discount'];
            $isSoldOut = $value['isSoldOut'];
            $startPromo = false;
            $endPromo = false;

            if(intval($isPromote) === 1){
                $promo = $this->promoManager->applyDiscount($price, $startDate,$endDate,$isPromote,$promoType, $discount);
                $startPromo = $promo['startPromo'];
                $endPromo = $promo['endPromo'];
                $product[$key]['originalPrice'] = $originalPrice = $price;
                $userPurchaseCount = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                            ->getUserPurchaseCountByPromo($buyerId,$promoType);
                
                $promoArray = $CI->config->item('Promo')[$promoType]; 
                if(($userPurchaseCount[0]['cnt'] >= $promoArray['purchase_limit']) || 
                (!$promoArray['is_buyable_outside_promo'] && !$startPromo)){
                    $product[$key]['canPurchase'] =  false;
                }
                else{
                    $product[$key]['canPurchase']   = true;
                }
        
                $dateToParam = date('Y-m-d',strtotime($endDate));
                if($dateToParam === '0001-01-01' ){
                    $dateToParam = date('Y-m-d');
                }

                $soldPrice = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                            ->getSoldPrice($productId, date('Y-m-d',strtotime($startDate)), $dateToParam);
                                            
                $price = ($isSoldOut) ? $soldPrice : $promo['price']; 
            }
            else{
                $product[$key]['originalPrice'] = $originalPrice = $price;
                $product[$key]['canPurchase'] = true;
                if(intval($discount) > 0){
                    $price = $price * (1.0-($discount/100.0));
                }  
            }
            
            if($originalPrice <= 0){
                $product[$key]['percentage'] = 0;
            }
            else{
                $product[$key]['percentage'] = ($originalPrice - $price)/$originalPrice * 100.00;
            }

            $product[$key]['price'] = $price;
            $product[$key]['isFreeShipping'] = $this->em->getRepository('EasyShop\Entities\EsProductShippingHead')
                            ->getShippingTotalPrice($productId);
        }

        return $product;
    }

    /**
     * function that will get all possible keyword tied on selected product
     * @return [type] [description]
     */
    public function generateSearchKeywords($productId)
    { 
        $CI = get_instance(); 
        $collectionHelper = $CI->kernel->serviceContainer['collection_helper']; 
        
        $products = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->find($productId);
        
        $category = $products->getCat()->getIdCat();
        $brand = $products->getBrand()->getName();
        $username = $products->getMember()->getUsername();

        $categoryParent = $this->em->getRepository('EasyShop\Entities\EsCat')
                                            ->getParentCategoryRecursive($category);

        $attributes = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->getAttributes($productId);

        $organizedAttributes = $collectionHelper->organizeArray($attributes);

        $attributesString = "";
        foreach ($organizedAttributes as $key => $value) {
            $attributesString .= $key.' ';
            foreach ($value as $attrValue) {
                $attributesString .= $attrValue.' ';
            }
        }

        $categoryString = "";
        foreach ($categoryParent as $key => $value) {
            $categoryString .= $value['name'].' ';
        }

        $arrayKeyword = array(
                        $products->getName(),
                        $products->getKeywords(),
                        $products->getCatOtherName(),
                        $brand,
                        $username,
                        $attributesString,
                        $categoryString
                    );

        $finalSearchKeyword = preg_replace('/\s+/', ' ',implode(' ', $arrayKeyword));

        $products->setSearchKeyword($finalSearchKeyword); 
        $this->em->flush();

        return true;
    }

    /**
     *  Classify product under custom category
     *  Pass an array of productIDs for batch updating.
     *
     *  @param array $prodId
     *  @param integer $catId
     */
    public function setProductCustomCategory($prodId, $catId, $memberId)
    {
        $memberObj = $this->em->find('EasyShop\Entities\EsMember', $memberId);
        $category = $this->em->getRepository('EasyShop\Entities\EsMemberCat')
                            ->findOneBy(array(
                                            'idMemcat' => $catId,
                                            'member' => $memberObj
                                        ));
        if( !is_array($prodId) ){
            $prodId = array($prodId);
        }

        foreach($prodId as $productId){
            $product = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                ->findOneBy(array(
                                                'idProduct' => $productId,
                                                'member' => $memberObj
                                            ));
            $memProd = new EsMemberProdcat();
            $memProd->setMemcat($category)
                    ->setProduct($product);
            $this->em->persist($memProd);
        }

        $this->em->flush();
    }

    /**
     *  Get parent category of products uploaded by user
     *
     *  @param integer $memberId
     *
     *  @return array
     */
    public function getAllUserProductParentCategory($memberId)
    {
        $defaultCatImg = "assets/images/default_icon_small.png";
        $vendorCategories = array();

        $rawVendorCategories = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                    ->getUserProductParentCategories($memberId);

        foreach( $rawVendorCategories as $vendorCategory ){
            if( !isset($vendorCategories[$vendorCategory['parent_cat']]) && intval($vendorCategory['parent_cat']) !== 1 ){
                $catImg = "assets/" . substr($vendorCategory['p_cat_img'],0,strrpos($vendorCategory['p_cat_img'],'.')) . "_small.png";
                if( $vendorCategory['p_cat_img'] !== "" && file_exists($catImg)){
                    $categoryImage = $catImg;
                }
                else{
                    $categoryImage = $defaultCatImg;
                }
                
                $vendorCategories[$vendorCategory['parent_cat']] = array(
                    'name' => $vendorCategory['p_cat_name'],
                    'slug' => $vendorCategory['p_cat_slug'],
                    'child_cat' => array(),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => base_url(). 'category/' . $vendorCategory['p_cat_slug'],
                    'cat_img' => $categoryImage
                );
            }
            // For products whose parent is 'PARENT'
            else if( !isset($vendorCategories[$vendorCategory['parent_cat']]) && intval($vendorCategory['parent_cat']) === 1 ) {
                $vendorCategories[$vendorCategory['parent_cat']] = array(
                    'name' => 'Others',
                    'slug' => '',
                    'child_cat' => array(),
                    'products' => array(),
                    'product_count' => 0,
                    'cat_link' => '',
                    'cat_img' => $defaultCatImg
                );
            }
            $vendorCategories[$vendorCategory['parent_cat']]['child_cat'][] = $vendorCategory['cat_id'];
            $vendorCategories[$vendorCategory['parent_cat']]['product_count'] += $vendorCategory['prd_count'];
        }

        return $vendorCategories;
    }

}