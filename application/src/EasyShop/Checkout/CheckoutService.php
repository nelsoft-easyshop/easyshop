<?php

namespace EasyShop\Checkout;

use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
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
     * Payment Service Class
     *
     * @var EasyShop\PaymentService\PaymentService
     */
    private $paymentService;

    /**
     * Cart Manager Class
     *
     * @var EasyShop\Product\ProductShippingLocationManager
     */
    private $shippingLocationManager;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em, $productManager, $promoManager, $cartManager, $paymentService, $shippingLocationManager)
    {
        $this->em = $em;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->cartManager = $cartManager;
        $this->paymentService = $paymentService;
        $this->shippingLocationManager = $shippingLocationManager;
    }

    /**
     * Validate Cart before checking out
     * @param  EasyShop\Entities\EsMember $member
     * @return array
     */
    public function validateCartContent($member, $cartContent = [])
    {
        if(empty($cartContent)){
            $cartContent = $this->cartManager->getValidatedCartContents($member->getIdMember());
        }

        foreach ($cartContent as $key => $value) {
            $productId = $value['id'];
            $itemId = $value['product_itemID'];
            $quantity = $value['qty'];
            $product = $this->productManager->getProductDetails($productId);
            $shippingDetails = $this->shippingLocationManager
                                    ->getProductShippingLocations($productId, $itemId, $member);
            $isAvailableInLocation = false;
            $shipmentFee = 0;
            if($shippingDetails && count($shippingDetails) >= 1){ 
                $isAvailableInLocation = true; 
                $shipmentFee = $shippingDetails[0]['price'];
            }

            $cartContent[$key]['canPurchaseWithOther'] = $this->canPurchaseWithOtherProduct($product) && count($cartContent) === 1;
            $cartContent[$key]['hasNoPuchaseLimitRestriction'] = $this->isPurchaseLimitReach($product, $member->getIdMember());
            $cartContent[$key]['isQuantityAvailable'] = $this->canPurchaseDesiredQuantity($product, $itemId, $quantity);
            $cartContent[$key]['shippingFee'] = $shipmentFee;
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

        if((int)$product->getIsPromote() === 1 && (int)$product->getStartPromo() === 1){
            return $promoConfig['cart_solo_restriction'];
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
        $quantityData = $this->productManager->getProductInventory($product, false, true);

        if(isset($quantityData[$itemId]['quantity'])){
            return $quantityData[$itemId]['quantity'] >= $quantity 
                   && $quantityData[$itemId]['quantity'] > 0;
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
        $paymentMethod = $this->paymentService->getUserPaymentMethod($memberId);

        $cartProduct['dragonpay'] = false;
        $cartProduct['paypal'] = false; 
        $cartProduct['cash_delivery'] = false;
        $cartProduct['pesopaycdb'] = false;
        $cartProduct['directbank'] = false;

        if($paymentMethod['all']){
            $cartProduct['dragonpay'] = true;
            $cartProduct['paypal'] = true; 
            $cartProduct['cash_delivery'] = true;
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
    public function checkoutCanContinue($cartData, $paymentType)
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
            if( !isset($item[$paymentString]) 
                || !$item[$paymentString]
                || !$item['canPurchaseWithOther']
                || !$item['hasNoPuchaseLimitRestriction']
                || !$item['isQuantityAvailable']
                || !$item['isAvailableInLocation'] ){
                $itemFail++;
            } 
        }

        return $itemFail === 0;
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
}

