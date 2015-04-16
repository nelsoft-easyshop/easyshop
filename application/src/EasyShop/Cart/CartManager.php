<?php

namespace EasyShop\Cart;

use Easyshop\Product\ProductManager as ProductManager;
use Easyshop\Promo\PromoManager as PromoManager;
use Easyshop\Cart\CartInterface as CartInterface;
use EasyShop\Product\ProductShippingLocationManager as ProductShippingLocationManager;


/**
 * Cart Manager Class
 *
 * @author Sam Gavinio <samgavinio@easyshop.ph>
 */
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
     * ProductShippingLocationManager Instance
     * @var EasyShop\Product\ProductShippingLocationManager
     */
    private $shippingLocationManager;
    
    /**
     * Constructor
     * @param EntityManager $em
     * @param Easyshop\Cart\CartInterface $cart
     * @param Easyshop\Product\ProductManager $productManager
     * @param Easyshop\Cart\CartInterface PromoManager
     *
     */
    public function __construct($em, CartInterface $cart, ProductManager $productManager, PromoManager $promoManager, ProductShippingLocationManager $shippingLocationManager)
    {
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->cart = $cart;
        $this->em = $em;
        $this->shippingLocationManager = $shippingLocationManager;
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
    
        if(!$product || (int)$product->getIsDraft() !== 0 || (int)$product->getIsDelete() !== 0 ){
            return false;
        }
        
        /**
         * Fix for strange error when the member cannot be obtained from the product object
         */
        $seller = $product->getMember();
        if(!$seller){
            $seller = $this->em->getRepository('EasyShop\Entities\EsProduct')
                               ->getSeller($productId);
        }       
        if((int)$seller->getIsActive() !== \EasyShop\Entities\EsMember::DEFAULT_ACTIVE || 
           (int)$seller->getIsBanned() !== \EasyShop\Entities\EsMember::NOT_BANNED){
            return false;
        }
        
        $cartProductAttributes = array();
        $validatedCartOptions = array();
        $finalPrice = $product->getFinalPrice();
        $totalOptionPrice = 0;
        $options  = empty($options) ? array() : $options;

        foreach($options as $key => $option){
            $explodedOption = explode("~", $option);
            $attrValue =  (strpos($option, "~")) ? $explodedOption[0] : $option;
            $attrPrice = isset($explodedOption[1]) ? $explodedOption[1] : null;
            $attrName = $key;
            $productAttribute = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                         ->getProductAttributeDetailByName($productId, $attrName, $attrValue,$attrPrice);

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

         
        $isListingOnly = $this->productManager->isListingOnly($product);
        if($isListingOnly){
            return false;
        }
        
        $inventoryDetails = $this->productManager->getProductInventory($product);
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
        $cartIndexName = $this->cart->getIndexName();
        $productImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                        ->getDefaultImage($productId);
        
        $itemData = array(
            'id' => $productId,
            'qty' => $cartItemQuantity,
            'price' => $finalPrice,
            'original_price' => $product->getOriginalPrice(),
            'name' => $product->getName(),
            'options' => $validatedCartOptions,
            'imagePath' => $productImage->getDirectory(),
            'imageFile' =>  $productImage->getFilename(),
            'member_id' => $seller->getIdMember(),
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
    public function getValidatedCartContents($memberId)
    {
        $member = $this->em->getRepository('EasyShop\Entities\EsMember')->find($memberId);

        if(!$member || !$member->getIsEmailVerify()){
            $this->cart->destroy();
            return array();
        }

        $cartContents = $this->cart->getContents();
        $cartIndexName = $this->cart->getIndexName();

        foreach($cartContents as $cartItem){
        
            $validationResult = $this->validateSingleCartContent($cartItem['id'], $cartItem['options'],  $cartItem['qty']);
            $productItem = $this->em->getRepository('EasyShop\Entities\EsProductItem')
                                    ->find($cartItem['product_itemID']);
            $itemData = $validationResult['itemData'];
            $product = $validationResult['product'];
            $serialRawOptions = serialize($cartItem['options']);
            $serialValidatedOptions = serialize($itemData['options']);
            $canBuyerDoPurchase = $product ? $this->canBuyerPurchaseProduct($product, $memberId) : false;

            if( !$canBuyerDoPurchase || intval($cartItem['id']) !==  intval($itemData['id']) ||
                $serialRawOptions !== $serialValidatedOptions ||
                intval($itemData['member_id']) === intval($memberId) || $product->getIsDraft() ||
                $product->getIsDelete() || intval($itemData['qty']) === 0
                || !$productItem)
            {
                $this->cart->removeContent($cartItem[$cartIndexName]);
            }
            else{
                $this->cart->updateContent($cartItem[$cartIndexName], $itemData);
            }

        }

        $this->cart->persist($memberId);

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
     * @param array $option
     * @return bool
     */
    public function addItem($productId, $quantity = 1, $option = array())
    {
        $isSuccessful = false;
        $quantityToInsert = $quantity;
        $cartContents = $this->cart->getContents(); 
        
        $validationResult = $this->validateSingleCartContent($productId,$option,$quantityToInsert);
        
        if(!$validationResult){
            return false;
        }

        $itemData = $validationResult['itemData'];
        if(empty($cartContents) || !is_array($option)){
            $this->cart->addContent($itemData);
        }
        else{
            $indexName = $this->cart->getIndexName();
            $isUpdate = false;
            foreach($cartContents as $cartRow){
                $optionCart =  serialize($cartRow['options']);
                $optionNew =  serialize($itemData['options']);
                if($optionCart === $optionNew && $cartRow['id'] === $itemData['id']){
                    $quantityToInsert = $quantityToInsert + $cartRow['qty'];
                    $quantityToInsert = $quantityToInsert > $itemData['maxqty'] ?  $itemData['maxqty'] : $quantityToInsert;
                    $quantityToInsert = $quantityToInsert < 0 ? 0 : $quantityToInsert;
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

        return true;
    }
    
    
    /**
     * Remove an item from the cart. Also persists the user
     * data into the database
     *
     * @param integer $memberId
     * @param integer $cartRowId
     */
    public function removeItem($memberId, $cartRowId)
    {
        if($this->cart->removeContent($cartRowId)){        
            $this->getValidatedCartContents($memberId);
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
        $product = $this->productManager->getProductDetails($cartItem['id']);
        $cartItem['qty'] = $quantity;

        if ((int)$product->getIsPromote() === 1 && (int)$product->getStartPromo() === 1){
            $promoQuantityLimit = $this->promoManager->getPromoQuantityLimit($product);
            if($quantity > $promoQuantityLimit){
                $cartItem['qty'] = $promoQuantityLimit;
            }
        }
        else{
            if($quantity > $itemAvailability){
                $cartItem['qty'] = $itemAvailability;
            }
        }
        
        $this->cart->updateContent($cartRowId, $cartItem);

        return (intval($quantity) === 0) ? FALSE : TRUE;
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
    
    /**
     * Synchs the cartData in the session with the cart data in the database
     * Used usually after successful login.
     *
     * @param integer $memberId
     */
    public function synchCart($memberId)
    {
        $userCartData = unserialize($this->em->find('EasyShop\Entities\EsMember', ['idMember' => $memberId])
                                            ->getUserData());   
        if($userCartData){
            foreach($userCartData as $rowId => $cartItem){
                if(!isset($cartItem[$this->cart->getIndexName()])){
                    continue;
                }
                $this->addItem($cartItem['id'], $cartItem['qty'], $cartItem['options']);
            }
        }
        return $this->cart->getContents(); 

    }

    /**
     * Get cart total shipping fee
     * @param  integer $cityLocation 
     * @param  integer $memberId 
     * @return float
     */
    public function getCartShippingFee($cityLocation, $memberId, $validate = false)
    {
        $cityLocation = (int)$cityLocation;
        if($cityLocation){
            $region = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                           ->getParentLocation($cityLocation); 
            $regionLocation = $region->getIdLocation(); 
            $islandId = $region->getParent()->getIdLocation(); 
            $cartContents = $this->getValidatedCartContents($memberId);
            $totalFee = 0;
            foreach ($cartContents as $item) {
                $shippingFee = $this->shippingLocationManager
                                    ->getProductItemShippingFee(
                                        $item['product_itemID'], 
                                        $cityLocation, 
                                        $regionLocation, 
                                        $islandId
                                    );

                if($validate && $shippingFee === null){
                    return false;
                }

                $additionalFee = $shippingFee !== null ? bcmul($shippingFee, $item['qty'], 4) : 0;
                $totalFee = bcadd($additionalFee, $totalFee, 4);
            }

            return $totalFee; 
        }

        return false;
    }


}
