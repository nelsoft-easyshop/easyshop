<?php

namespace EasyShop\Cart;

use Easyshop\Product\ProductManager as ProductManager;
use Easyshop\Promo\PromoManager as PromoManager;
use Easyshop\Cart\CartInterface as CartInterface;

class CartManager
{

    /**
     * The cart implementation
     *
     */
    private $cart;
    
    /**
     * Product Manager instance
     *
     * @var Easyshop\Product\ProductManager
     */
     private $productManager;

    /**
     * Promo Manager instance
     *
     * @var Easyshop\Promo\PromoManager
     */
     private $promoManager;
     
    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;


    /**
     * Promo Config Array
     *
     * @var  mixed
     */
    private $promoConfiguration;
    
    /**
     * Constructor
     * @param EntityManager $em
     * @param Easyshop\Cart\CartInterface $cart
     * @param Easyshop\Product\ProductManager $productManager
     * @param Easyshop\Cart\CartInterface PromoManager
     *
     */
    public function __construct($em, CartInterface $cart, ProductManager $productManager, PromoManager $promoManager)
    {
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->cart = $cart;
        $this->em = $em;
    }
    

    /**
     * Validates single cart content. Returns the validated cart data
     * and a fully hydrated product object.
     *
     * @param integer $productId
     * @param array $options
     * @param integer $quantity
     * @return mixed
     */
    public function validateSingleCartContent($productId, $options, $quantity)
    {
        $product = $this->productManager->getProductDetails($productId);

        $cartProductAttributes = array();
        $validatedCartOptions = array();
        $finalPrice = $product->getPrice();
        $totalOptionPrice = 0;
        $options  = empty($options) ? array() : $options;
        
        foreach($options as $key => $option){
            $attrValue =  (strpos($option, "~")) ? explode("~", $option)[0] : $option;
            $attrName = $key;
            $productAttribute = $this->em->getRepository('EasyShop\Entities\EsProduct')
                            ->getProductAttributeDetailByName($product->getIdProduct(), $attrName, $attrValue);
            if(empty($productAttribute)){
                return false;
            }
            $totalOptionPrice += $productAttribute['attr_price'];
            $validatedCartOptions[$key] = $productAttribute['attr_value'] . '~' . $productAttribute['attr_price'];
            array_push($cartProductAttributes, array('id' => $productAttribute['attr_id'], 'is_other' => $productAttribute['is_other']));
        }
        $finalPrice += $totalOptionPrice;

        /**
         * Validate attributes in the cart with the attributes in the database
         */
        $inventoryDetails = $this->productManager->getProductInventory($product, false, false, $product->getStartPromo());
        $areAllAttributesMatched = false;
        $productItemId = null;

        if (count($inventoryDetails) === 1 && reset($inventoryDetails)['is_default']) {
            $itemAvailability = reset($inventoryDetails)['quantity'];
            $productItemId = reset(array_keys($inventoryDetails));
            $areAllAttributesMatched = true;
        }
        else{
            foreach ($inventoryDetails as $key => $inventoryDetail) {
                $matchCount = 0;
                foreach ($inventoryDetail['product_attribute_ids'] as $inventoryProductAttribute) {
                    if(!in_array( array('id' => $inventoryProductAttribute['id'], 'is_other' => $inventoryProductAttribute['is_other'])  , $cartProductAttributes)){
                        break;
                    }
                    else{
                        $matchCount++;
                    }
                }
                if($matchCount === count($cartProductAttributes)){
                    $areAllAttributesMatched = true;
                    $productItemId = $key;
                    break;
                }
            }
            $itemAvailability = $inventoryDetail['quantity'];
        }

        if(!$areAllAttributesMatched){
            return false;
        }
        
        $cartItemQuantity = ($quantity > $itemAvailability) ? $itemAvailability : $quantity;

        $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                        ->getDefaultImage($product->getIdProduct());
        
        $itemData = array(
            'id' => $product->getIdProduct(),
            'qty' => $cartItemQuantity,
            'price' => $finalPrice,
            'original_price' => $product->getOriginalPrice(),
            'name' => $product->getName(),
            'options' => $validatedCartOptions,
            'img' => $productImage->getProductImagePath(),
            'member_id' => $product->getMember()->getIdMember(),
            'brief' => $product->getBrief(),
            'product_itemID' => $productItemId, 
            'maxqty' => $itemAvailability,
            'slug' => $product->getSlug(),
            'is_promote' => $product->getIsPromote(),
            'additional_fee' => $totalOptionPrice,
            'promo_type' => $product->getPromoType(),
            'start_promo' => $product->getStartPromo(),
        );
        
        return array('itemData' => $itemData, 'product' => $product);
    }
    

    
    /**
     * Returns the validated contents of the cart
     *
     * @param integer $memberId
     * @return mixed
     */
    public function getValidateCartContents($memberId)
    {
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($memberId);
        if(!$member->getIsEmailVerify()){
            $this->cart->destroy();
            return false;
        }
    
        $cartContents = $this->cart->getContents();        
        foreach($cartContents as $cartItem){
        
            $validationResult = $this->getValidatedCartContent($cartItem['id'], $cartItem['options'],  $cartItem['qty']);
            $itemData = $validationResult['itemData'];
            $product = $validationResult['product'];
        
            $serialRawOptions = serialize($cartItem['options']);
            $serialValidatedOptions = serialize($itemData['options']);
            $canBuyerDoPurchase = $this->canBuyerPurchaseProduct($product, $memberId);
            $cartIndexName = $this->cart->getIndexName();
           
            if( !$canBuyerDoPurchase || $cartItem['id'] !=  $itemData['id'] || 
                $serialRawOptions != $serialValidatedOptions ||
                $itemData['member_id'] == $memberId || $product->getIsDraft() != 0 ||
                $product->getIsDelete() != 0 || $cartItemQuantity == 0)
            {
                $this->cart->removeContent($cartItem[$cartIndexName]);
            }
            else{
                $this->cart->updateContent($cartItem[$cartIndexName], $itemData);
            }

        }
        return  $this->cart->getContents();
            
    }
    
    
    /**
     * Determines if a buyer can buy a product
     *
     * @param EasyShop\Entities\Product $product
     * @param integer $memberId
     * @return bool
     */
    public function canBuyerPurchaseProduct($product, $memberId)
    {

        $promoBoughtCount = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                ->getPromoPurchaseCountForMember($memberId, $product->getPromoType());
        $promoConfig = $this->promoManager->getPromoConfig($product->getPromoType());
        if(($promoBoughtCount >= $promoConfig['purchase_limit']) || 
           ($product->getIsPromote() && !$promoConfig['is_buyable_outside_promo'] && !$product->getStartPromo())
        ){
            return false;
        }
        return true;
    }
    

    /**
     * Adds an item to the cart. Performs validations and ensures that
     * the quantity limit is not exceeded. If the item already exists,
     * the quantity is simply updated.
     *
     * @param integer $productId
     * @param integer $quantity
     * @param integer $maxAvailability
     * @param array $option
     * @param integer $optionLength
     * @return bool
     */
    public function addItem($productId, $quantity = 1, $maxAvailability, $option = array(), $optionLength)
    {
        $cartContents = $this->cart->getContents(); 
        
        if($optionLength !== count($option);){
            $response = false;
        }
        else{
            $quantityToInsert = $quantity;
            $validationResult = $this->validateSingleCartContent($productId,$option,$quantityToInsert);
            $itemData = $validationResult['itemData'];
            $response = true;
            if(empty($cartContents) || !is_array($option)){
                $this->cart->addContent($itemData);
            }
            else{
                $indexName = $this->cart->getIndexName();
                $isUpdate = false;
                foreach($cartContents as $cartRow){
                    $optionCart =  serialize($cartRow['options']);
                    $opttionNew =  serialize($itemData['options']);
                    if($optionCart == $optionNew && $cartRow['id'] == $itemData['id']){
                        $quantityToInsert = $quantityToInsert + $cartRow['qty'];
                        $quantityToInsert =  $quantityToInsert > $maxAvailability ? $maxAvailability : $quantityToInsert
                        $itemData['qty'] = $quantityToInsert;
                        $isUpdate = true;
                        break;     
                    }
                }
                if($isUpdate){
                    $this->cart->updateContent($cartRow[$indexName],$itemData);
                }
                else{
                    $this->cart->addContent($itemData);
                }
            }
            
        }
        
        return $response;
    }
    
    
    /**
     * Remove an item from the cart. Also persists the user
     * data into the database
     *
     * @param integer $memberId
     * @param inetger $cartRowId
     */
    public function removeItem($memberId, $cartRowId)
    {
        if($this->cart->removeContent($cartRowId)){ 
            $result=array(
                'isSuccess'=>true,
                'totalPrice'=>  $this->cart->getTotalPrice(),
                'numberOfItems'=> $this->cart->getSize()
            );            
            $this->validateCartContents();
            $this->cart->persist($memberId);
            return true;
        }
        return false;
    }

    /**
     * Change the quantity of an item in the cart
     *
     * @param integer $cartRowId
     * @Param integer $quantity
     *
     * @Return bool
     */
    public function changeItemQuantity($cartRowId, $quantity)
    {
        $cartItem = $this->cart->getSingleItem($cartRowId);
        $itemAvailability = $cartItem['maxqty'];
        $product = $this->productManager->getProductDetails($cartItem);
        $cartItem['qty'] = $quantity;

        if (intval($product->getIsPromote()) === 1){
            $promoQuantityLimit = $this->promoManager->getPromoQuantityLimit($product->getIdProduct());
            if($quantity > $promoQuantityLimit){
                $cartItem['qty'] = $promoQuantityLimit;
            }
        }
        else{
            if($quantity > $itemAvailability){
                $cartItem['qty'] = $itemAvailability;
            }
        }
        
        return $this->cart->update($cartRowId, $cartItem);
        
    }
    
    /**
     * Returns the cart object
     *
     * @return EasyShop\Cart\CartInterface
     */
    public function getCartObject()
    {
        return $this->cart;
    }



}
