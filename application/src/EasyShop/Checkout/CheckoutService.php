<?php

namespace EasyShop\Checkout;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsAddress as EsAddress;
/**
 * Checkout Service Class
 *
 */
class CheckoutService
{

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Product Manager Class
     *
     * @var EasyShop\Product\ProductManager
     */
    private $productManager;

    /**
     * Promo Manager Class
     *
     * @var EasyShop\Promo\PromoManager
     */
    private $promoManager;

    /**
     * Cart Manager Class
     *
     * @var EasyShop\Cart\CartManager
     */
    private $cartManager; 

    /**
     * Cart Manager Class
     *
     * @var EasyShop\Product\ProductShippingLocationManager
     */
    private $shippingLocationManager;

    /**
     * Config Loader
     *
     * @var EasyShop\Config\ConfigLoader
     */
    private $configLoader;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em, $productManager, $promoManager, $cartManager, $shippingLocationManager, $configLoader)
    {
        $this->em = $em;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->cartManager = $cartManager; 
        $this->shippingLocationManager = $shippingLocationManager;
        $this->configLoader = $configLoader;
    }

    /**
     * Include Cart Content Validation before checking out
     * @param  EasyShop\Entities\EsMember $member
     * @return array
     */
    public function includeCartItemValidation($member)
    {
        $cartContent = $this->cartManager->getValidatedCartContents($member->getIdMember());

        foreach ($cartContent as $key => $value) {
            $productId = $value['id'];
            $itemId = $value['product_itemID'];
            $quantity = $value['qty'];
            $product = $this->productManager->getProductDetails($productId);
            $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                ->findOneBy([
                                    'idMember' => $member->getIdMember(),
                                    'type' => EsAddress::TYPE_DELIVERY,
                                ]);

            $shipmentFee = 0;
            $isAvailableInLocation = false;
            if($address){
                $city = $address->getCity()->getParent()->getIdLocation();
                $region = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                               ->getParentLocation($city);
                $regionId = $region->getIdLocation(); 
                $majorIsland = $region->getParent()->getIdLocation();
                $shipmentFee = $this->shippingLocationManager
                                    ->getProductItemShippingFee($itemId, $city, $regionId, $majorIsland);

                if($shipmentFee !== null){
                    $isAvailableInLocation = true;
                }
            }

            $cartContent[$key]['canPurchaseWithOther'] = $this->canPurchaseWithOtherProduct($product);
            $cartContent[$key]['hasNoPuchaseLimitRestriction'] = $this->isPurchaseLimitReach($product, $member->getIdMember());
            $cartContent[$key]['isQuantityAvailable'] = $this->canPurchaseDesiredQuantity($product, $itemId, $quantity);
            $cartContent[$key]['shippingFee'] = $shipmentFee !== null ? $shipmentFee : 0;
            $cartContent[$key]['isAvailableInLocation'] = $isAvailableInLocation;
            $this->applyPaymentTypAvailable($cartContent[$key], $product);
        }

        return $cartContent;
    }

    /**
     * Check if product in cart can purchase with other product
     * @param  EasyShop\Entities\EsProduct $product
     * @return boolean
     */
    public function canPurchaseWithOtherProduct($product)
    {
        $promoConfig = $this->promoManager->getPromoConfig($product->getPromoType());

        if((bool)$product->getIsPromote() && (bool)$product->getStartPromo()){
            return $promoConfig['cart_solo_restriction'] === false;
        }

        return true;
    }

    /**
     * check if desired quantity is available
     * @param  EasyShop\Entities\EsProduct  $product
     * @param  integer $itemId
     * @param  integer $quantity
     * @return boolean
     */
    public function canPurchaseDesiredQuantity($product, $itemId, $quantity)
    {
        $quantityData = $this->productManager->getProductInventory($product);

        if(isset($quantityData[$itemId]['quantity'])){
            return $quantityData[$itemId]['quantity'] > 0
                   && $quantityData[$itemId]['quantity'] >= $quantity;
        }

        return false;
    }

    /**
     * Check if user is reach the purchase limit on specific product
     * @param  EasyShop\Entities\EsProduct $product
     * @param  integer  $memberId
     * @return boolean
     */
    public function isPurchaseLimitReach($product, $memberId)
    {
        return $this->cartManager->canBuyerPurchaseProduct($product, $memberId);
    }

    /**
     * apply boolean payment type checking
     * @param  array  $cartProduct
     * @param  EasyShop\Entities\EsProduct $product
     */
    public function applyPaymentTypAvailable(&$cartProduct, $product)
    {   
        $memberId = $cartProduct['member_id'];
        $paymentMethod = $this->getUserPaymentMethod($memberId);

        $cartProduct['dragonpay'] = false;
        $cartProduct['paypal'] = false; 
        $cartProduct['cash_delivery'] = false;
        $cartProduct['pesopaycdb'] = false;
        $cartProduct['directbank'] = false;

        if($paymentMethod['all']){
            $cartProduct['dragonpay'] = true;
            $cartProduct['paypal'] = true; 
            $cartProduct['cash_delivery'] = $product->getIsCod();
            $cartProduct['pesopaycdb'] = true;
            $cartProduct['directbank'] = true;
        }
        else{
            foreach ($paymentMethod['payment_method'] as $payValue) {
                if((int)$payValue === EsPaymentMethod::PAYMENT_PAYPAL){
                    $cartProduct['paypal'] = true; 
                }
                elseif ((int)$payValue === EsPaymentMethod::PAYMENT_DRAGONPAY){
                    $cartProduct['dragonpay'] = true; 
                }
                elseif ((int)$payValue === EsPaymentMethod::PAYMENT_PESOPAYCC){
                    $cartProduct['pesopaycdb'] = true; 
                }
                elseif ((int)$payValue === EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT){
                    $cartProduct['directbank'] = true; 
                }
                elseif ((int)$payValue === EsPaymentMethod::PAYMENT_CASHONDELIVERY && $product->getIsCod()){
                    $cartProduct['cash_delivery'] = true;
                }
            }
        }
    }

    /**
     * Check if checkout request can continue to checkout
     * @param  array  $cartData    [description]
     * @param  string $paymentType [description]
     * @return boolean
     */
    public function checkoutCanContinue($cartData, $paymentType, $validatePaymentType = true)
    {
        $itemFail = 0;
        $paymentString = "";
        if((int)$paymentType === EsPaymentMethod::PAYMENT_PAYPAL){
            $paymentString = 'paypal';
        }
        elseif ((int)$paymentType === EsPaymentMethod::PAYMENT_DRAGONPAY){
            $paymentString = 'dragonpay';
        }
        elseif ((int)$paymentType === EsPaymentMethod::PAYMENT_PESOPAYCC){
            $paymentString = 'pesopaycdb';
        }
        elseif ((int)$paymentType === EsPaymentMethod::PAYMENT_DIRECTBANKDEPOSIT){
            $paymentString = 'directbank';
        }
        elseif ((int)$paymentType === EsPaymentMethod::PAYMENT_CASHONDELIVERY){ 
            $paymentString = 'cash_delivery';
        }

        foreach ($cartData as $item) { 
            if($validatePaymentType){
                if( !isset($item[$paymentString]) 
                    || !$item[$paymentString]
                    || !$item['canPurchaseWithOther']
                    || !$item['hasNoPuchaseLimitRestriction']
                    || !$item['isQuantityAvailable']
                    || !$item['isAvailableInLocation'] ){
                    $itemFail++;
                }
            }
            else{
                if( !$item['canPurchaseWithOther']
                    || !$item['hasNoPuchaseLimitRestriction']
                    || !$item['isQuantityAvailable']
                    || !$item['isAvailableInLocation'] ){
                    $itemFail++;
                }
            }
        }

        return $itemFail === 0 && count($cartData) !== 0;
    }

    /**
     * Return appropriate payment method based on string
     * @param  string $paymentString
     * @return integer
     */
    public function getPaymentTypeByString($paymentString)
    {
        $paymentType = 0;
        if($paymentString === "paypal"){ 
            $paymentType = EsPaymentMethod::PAYMENT_PAYPAL;
        }
        elseif($paymentString === "dragonpay"){
            $paymentType = EsPaymentMethod::PAYMENT_DRAGONPAY;
        }
        elseif($paymentString === "cash_delivery"){
            $paymentType = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        }

        return $paymentType;
    }

    /**
     * Get all payment type available
     * @param  mixed $cartData [description]
     * @return mixed
     */
    public function getPaymentTypeAvailable($cartData)
    {
        $configPromo = $this->configLoader->getItem('promo','Promo');
       
        $paymentType = $configPromo[0]['payment_method'];
     
        foreach ($cartData as $item) { 
            $paymentType = array_intersect ( $paymentType , $configPromo[$item['promo_type']]['payment_method']);
        }

        return $paymentType;
    }

    /**
     * Get all possible error during checkout
     * @param  mixed $cartData
     * @return mixed
     */
    public function getCheckoutError($cartData)
    {   
        $errorMessage = [];
        $paymentTypeError = [];
        foreach ($cartData as $item) {
            if( !$item['canPurchaseWithOther'] ){
                $errorMessage[] = "One of your items can only be purchased  individually.";
            }
            if( !$item['hasNoPuchaseLimitRestriction'] ){
                $errorMessage[] = "You have exceeded your purchase limit for a promo of an item in your cart.";
            }
            if( !$item['isQuantityAvailable'] ){
                $errorMessage[] = "The availability of one of your items is less than your desired quantity. 
                                    Someone may have purchased the item before you can complete your payment. 
                                    Check the availability of your item and try again.";
            }
            if( !$item['isAvailableInLocation'] ){
                $errorMessage[] = "One or more of your item(s) is unavailable in your location.";
            }

            if(!$item['dragonpay']){
                $paymentTypeError[] = EsPaymentMethod::PAYMENT_DRAGONPAY;
            }
            if(!$item['paypal']){
                $paymentTypeError[] = EsPaymentMethod::PAYMENT_PAYPAL;
            }
            if(!$item['cash_delivery']){
                $paymentTypeError[] = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
            }
            if(!$item['pesopaycdb']){
                $paymentTypeError[] = EsPaymentMethod::PAYMENT_PESOPAYCC;
            }
        }

        return [
            'errorMessage' => array_unique($errorMessage),
            'paymentTypeError' => array_unique($paymentTypeError),
        ];
    }

    /**
     * Get payment method type per user
     * @param  integer $memberId
     * @return mixed
     */
    public function getUserPaymentMethod($memberId)
    {
        $configPromo = $this->configLoader->getItem('promo','Promo');
        $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethodUser')
                                  ->findBy(['member'=>$memberId]);
        
        $paymentArray = [];
        $paymentArray['payment_display'] = [];
        $paymentArray['all'] = false;
        if($paymentMethod){
            foreach ($paymentMethod as $key => $value) {
                $paymentArray['payment_method'][] = $value->getPaymentMethod()->getIdPaymentMethod();
                if ((int)$value->getPaymentMethod()->getIdPaymentMethod() === EsPaymentMethod::PAYMENT_PAYPAL) {
                    $paymentArray['payment_display'] = ['cdb' => 'Credit or Debit Card'];
                    $paymentArray['payment_display'] = ['paypal' => 'Paypal'];
                }
                elseif ((int)$value->getPaymentMethod()->getIdPaymentMethod() === EsPaymentMethod::PAYMENT_CASHONDELIVERY) {
                    $paymentArray['payment_display'] = ['cod' => 'Cash on Delivery'];
                }
                elseif ((int)$value->getPaymentMethod()->getIdPaymentMethod() === EsPaymentMethod::PAYMENT_DRAGONPAY) {
                    $paymentArray['payment_display'] = ['dragonpay' => 'Dragon Pay'];
                }
                elseif ((int)$value->getPaymentMethod()->getIdPaymentMethod() === EsPaymentMethod::PAYMENT_PESOPAYCC) {
                    $paymentArray['payment_display'] = ['pesopaycdb' => 'Peso Pay'];
                }
            }
        }
        else{
            $paymentArray['all'] = true;
            $paymentArray['payment_display'] = $configPromo[0]['payment_method'];
        }

        return $paymentArray;
    }
}

