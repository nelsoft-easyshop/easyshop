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
     * @var EasyShop\PaymentService\PaymentService
     */
    private $paymentService;

    /**
     * Constructor. Retrieves Entity Manager instance
     * 
     */
    public function __construct($em, $productManager, $promoManager, $cartManager, $paymentService)
    {
        $this->em = $em;
        $this->productManager = $productManager;
        $this->promoManager = $promoManager;
        $this->cartManager = $cartManager;
        $this->paymentService = $paymentService;
    }

    /**
     * Validate Cart before checking out
     * @param  array  $cartContent
     * @param  object $member
     * @return array
     */
    public function validateCartContent($cartContent, $member)
    {
        foreach ($cartContent as $key => $value) {
            $productId = $value['id'];
            $itemId = $value['product_itemID'];
            $quantity = $value['qty'];
            $product = $this->productManager->getProductDetails($productId);

            $cartContent[$key]['hasNoSoloRestriction'] = $this->canPurchaseWithOtherProduct($product);
            $cartContent[$key]['hasNoPuchaseLimitRestriction'] = $this->isPurchaseLimitReach($product, $member->getIdMember());
            $cartContent[$key]['isAvailableInLocation'] = $this->canPurchaseInLocation($product, $itemId, $member);
            $cartContent[$key]['isQuantityAvailable'] = $this->canPurchaseDesiredQuantity($product, $itemId, $quantity);
            $this->applyPaymentTypAvailable($cartContent[$key], $product);
        }

        return $cartContent;
    } 

    /**
     * Check if product in cart can purchase with other product
     * @param  object $product
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
     * Check if product in cart is available on users location
     * @param  object  $product
     * @param  integer $itemId
     * @param  object  $member
     * @return boolean
     */
    public function canPurchaseInLocation($product, $itemId, $member)
    {
        $shippingDetailRepo = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail');
        $addressRepo = $this->em->getRepository('EasyShop\Entities\EsAddress');

        $address = $addressRepo->findOneBy([
                                    'idMember' => $member->getIdMember(),
                                    'type' => EsAddress::TYPE_DELIVERY,
                                ]);

        if($address){
            $city = $address->getCity();
            $region = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')->getParentLocation($city);
            $majorIsland = $region->getParent();
            $locationDetails = $shippingDetailRepo->getShippingDetailsByLocation($product->getIdProduct(),
                                                                                 $itemId, 
                                                                                 $city, 
                                                                                 $region->getIdLocation(), 
                                                                                 $majorIsland->getIdLocation());
            return count($locationDetails) >= 1;
        }

        return false;
    }

    /**
     * [canPurchaseDesiredQuantity description]
     * @param  object  $product
     * @param  integer $itemId
     * @param  integer $quantity
     * @return boolean
     */
    public function canPurchaseDesiredQuantity($product, $itemId, $quantity)
    {
        $quantityData = $this->productManager->getProductInventory($product, false, true);

        return $quantity < $quantityData[$itemId]['quantity'];
    }

    /**
     * Check if user is reach the purchase limit on specific product
     * @param  object   $product
     * @param  integer  $memberId
     * @return boolean
     */
    public function isPurchaseLimitReach($product, $memberId)
    {
        return $this->cartManager->canBuyerPurchaseProduct($product, $memberId);
    }

    /**
     * [checkPaymentTypAvailable description]
     * @param  [type] $product [description]
     * @return [type]          [description]
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
}