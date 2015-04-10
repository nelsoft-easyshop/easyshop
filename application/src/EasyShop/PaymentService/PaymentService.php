<?php

namespace EasyShop\PaymentService;

use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsOrderShippingAddress;
use EasyShop\Entities\EsLocationLookup;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\Entities\EsOrderHistory;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsOrderProductStatus;
use EasyShop\Entities\EsOrderBillingInfo;
use EasyShop\Entities\EsBillingInfo;
use EasyShop\Entities\EsBankInfo;
use EasyShop\Entities\EsOrderProductAttr;
use EasyShop\Entities\EsOrderProductHistory;
use EasyShop\Entities\EsPaymentGateway;
use EasyShop\Entities\EsPoint;
use EasyShop\Entities\EsPointType as EsPointType;

/**
 * Payment Service Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PaymentService
{
    const STATUS_FAIL = 'f';

    const STATUS_SUCCESS = 's';

    const STATUS_PENDING = 'p';

    const SUCCESS_CODE = 0;

    /**
     * Gateway path
     *
     * @var string
     */
    private $gatewayPath = "EasyShop\PaymentGateways";
    
    /**
     * Main payment method
     *
     * @var mixed
     */
    private $primaryGateway = NULL;

    /**
     * Main payment method value holder
     *
     * @var mixed
     */
    private $primaryReturnValue = NULL;

    /**
     * Secondary payment method (points)
     *
     * @var mixed
     */
    private $pointGateway = NULL;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Http foundation Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * PointTracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;

    /**
     * Promo Manager instance
     *
     * @var EasyShop\Promo\PromoManager
     */
    private $promoManager;

    /**
     * Product Manager instance
     *
     * @var EasyShop\Product\ProductManager
     */
    public $productManager;

    /**
     * Post Array
     *
     * @var mixed
     */
    private $postArray;

    /**
     * Sms Notification Service
     *
     * @var  EasyShop\Notification\MobileNotification
     */
    private $smsService;

    /**
     * Email Notification Service
     *
     * @var EasyShop\Notification\EmailNotification
     */
    private $emailService;

    /**
     * Parser
     *
     * @var CI_Parser
     */
    private $parserLibrary;

    /**
     * Config Loader
     *
     * @var EasyShop\Config\ConfigLoader
     */
    public $configLoader;

    /**
     * XML Resource
     *
     * @var EasyShop\XML\Resource
     */
    private $xmlResourceService;

    /**
     * Social Media Manager
     *
     * @var EasyShop\SocialMedia\SocialMediaManager
     */
    private $socialMediaManager;

    /**
     * Language Loader
     *
     * @var EasyShop\LanguageLoader\LanguageLoader
     */
    private $languageLoader;

    /**
     * Message Manager
     *
     * @var EasyShop\Message\MessageManager
     */
    private $messageManager;

    /**
     * Soap client for dragonpay
     */
    public $dragonPaySoapClient;

    /**
     * Transaction Manager
     *
     * @var EasyShop\Transaction\TransactionManager
     */
    public $transactionManager;

    /**
     * Product Shipping Location Manager
     *
     * @var EasyShop\Product\ProductShippingLocationManager
     */
    private $productShippingManager;

    /**
     * Vendor Curl Class
     *
     * @var Curl
     */
    public $curlService;

    /**
     * Checkout service
     * @var EasyShop\Checkout\CheckoutService
     */
    public $checkOutService;

    private $paymentConfig;

    /**
     * Constructor
     * 
     */
    public function __construct($em, 
                                $request, 
                                $pointTracker, 
                                $promoManager, 
                                $productManager, 
                                $emailService,
                                $smsService, 
                                $parserLibrary,
                                $configLoader,
                                $xmlResourceService,
                                $socialMediaManager,
                                $languageLoader,
                                $messageManager,
                                $dragonPaySoapClient,
                                $transactionManager,
                                $productShippingManager,
                                $curlService,
                                $checkOutService)
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
        $this->promoManager = $promoManager;
        $this->productManager = $productManager;
        $this->smsService = $smsService;
        $this->emailService = $emailService;
        $this->parserLibrary = $parserLibrary;
        $this->configLoader = $configLoader;
        $this->xmlResourceService = $xmlResourceService;
        $this->socialMediaManager = $socialMediaManager;
        $this->languageLoader = $languageLoader;
        $this->messageManager = $messageManager;
        $this->dragonPaySoapClient = $dragonPaySoapClient; 
        $this->transactionManager = $transactionManager; 
        $this->productShippingManager = $productShippingManager;
        $this->curlService = $curlService;
        $this->checkOutService = $checkOutService;

        if(!defined('ENVIRONMENT') || strtolower(ENVIRONMENT) == 'production'){ 
            $this->paymentConfig = $this->configLoader->getItem('payment','production'); 
        }
        else{ 
            $this->paymentConfig = $this->configLoader->getItem('payment','testing'); 
        }
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $paymentMethods Parameters for each gateway
     */
    public function initializeGateways($paymentMethods)
    {
        if(count($paymentMethods) > 1){
            // Search array for point gateway
            foreach (array_keys($paymentMethods) as $key) {
                if(strpos(strtolower($key), 'point') !== false){
                    $this->pointGateway = new \EasyShop\PaymentGateways\PointGateway(
                        $this->em,
                        $this->request,
                        $this->pointTracker,
                        $this,
                        $paymentMethods[$key]
                        );
                    unset($paymentMethods[$key]);
                    break;
                }
            }
        }

        // Retrieve Primary gateway
        $primaryGatewayValues = reset($paymentMethods);
        $path = $this->gatewayPath . "\\" . $primaryGatewayValues['method'] . "Gateway";
        $this->primaryGateway = new $path(
                    $this->em,
                    $this->request,
                    $this->pointTracker,
                    $this,
                    $primaryGatewayValues
                    );
    }

    /**
     * Computes Shipping Fee and Reorganizes Data (processData)
     * 
     * @param mixed $itemList List of items to compute shipping fee
     * @param int $address Used for shipping fee calculation
     *
     * @return mixed
     */
    public function computeFeeAndParseData($itemList, $address)
    {
        $city = ($address > 0 ? $address : 0);
        $region = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')->getParentLocation($city);
        $majorIsland = $region->getParent();

        $grandTotal = 0;
        $productstring = "";
        $name = "";
        $totalAdditionalFee = 0;
        $toBeLocked = array();
        $promoItemCount = 0;
        
        foreach ($itemList as $key => $value) {
            $sellerId = $value['member_id'];
            $productId = $value['id'];
            $orderQuantity = $value['qty'];
            $price = $value['price']; 
            $promoItemCount = ($value['is_promote'] == 1) ? $promoItemCount += 1 : $promoItemCount += 0;
            $productItem =  $value['product_itemID'];
            $shipping_amt = $this->productShippingManager
                                 ->getProductItemShippingFee($productItem, $city, $region->getIdLocation(), $majorIsland->getIdLocation());
            $shipping_amt = $shipping_amt !== null ? $shipping_amt : 0 ;
            $otherFee = $shipping_amt * $orderQuantity;
            $totalAdditionalFee += $otherFee;
            $total =  $value['subtotal'] + $otherFee;
            $optionCount = count($value['options']);
            $optionString = '';
            foreach ($value['options'] as $keyopt => $valopt) {
                $optValueandPrice = explode('~', $valopt);
                $optionString .= '(-)'.$keyopt.'[]'.$optValueandPrice[0].'[]'.$optValueandPrice[1];
            }

            $optionString = ($optionCount <= 0) ? '0[]0[]0' : substr($optionString,3); 
            $productstring .= '<||>'.$sellerId."{+}".$productId."{+}".$orderQuantity."{+}".$price."{+}".$otherFee."{+}".$total."{+}".$productItem."{+}".$optionCount."{+}".$optionString;
            $itemList[$key]['otherFee'] = $otherFee; 
            $sellerDetails = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find($sellerId);
            $itemList[$key]['seller_username'] = $sellerDetails->getUsername();
            $grandTotal += $total;
            $name .= " ".$value['name'];
            $toBeLocked[$productItem] = $orderQuantity;
        }

        $productstring = substr($productstring,4);
        return array(
            'totalPrice' => round(floatval($grandTotal),2), 
            'newItemList' => $itemList,
            'productstring' => $productstring,
            'productName' => $name,
            'toBeLocked' => $toBeLocked,
            'othersumfee' => round(floatval($totalAdditionalFee),2), 
            'thereIsPromote' => $promoItemCount,
            );
    }

    /**
     * Validate Cart Data (resetPriceAndQty)
     * 
     * @param mixed  $carts User Session data
     * @param string $pointsAllocated point allocated
     * @param bool   $excludeMemberId
     *
     * @return mixed
     */
    public function validateCartData($carts, $pointsAllocated = "0.00", $excludeMemberId = 0)
    {
        $condition = true;
        $itemArray = $carts['choosen_items'];
        $availableItemCount = 0;

        foreach($itemArray as $key => $value){

            $productId = $value['id'];
            $itemId = $value['product_itemID'];

            $productArray = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                     ->find($productId);

            /* Get actual price, apply any promo calculation */
            $this->promoManager->hydratePromoData($productArray);

            /** NEW QUANTITY **/
            $productInventoryDetail = $this->productManager->getProductInventory($productArray, false, $condition, $excludeMemberId);
            $maxQty = $productInventoryDetail[$itemId]['quantity'];
            $qty = $value['qty'];
            $itemArray[$value['rowid']]['maxqty'] = $maxQty;
            $availableItemCount = ($maxQty >= $qty ? $availableItemCount + 1: $availableItemCount + 0);

            /** NEW PRICE **/
            $promoPrice = $productArray->getFinalPrice(); 
            $additionalPrice = $value['additional_fee'];
            $finalPromoPrice = $promoPrice + $additionalPrice; 
            $itemArray[$value['rowid']]['price'] = $finalPromoPrice;
            $subtotal = $finalPromoPrice * $qty;
            $itemArray[$value['rowid']]['subtotal'] = $subtotal;
        }

        return ['itemCount' => $availableItemCount, 
                'itemArray' => $itemArray];
    }

    /**
     * Executes payment transaction for all registered gateways
     *
     * @param mixed $paymentMethods Parameters for each gateway
     * @param mixed $validatedCart Validated cart content
     * @param int $memberId User Member Id
     *
     * @return mixed
     */    
    public function pay($paymentMethods, $validatedCart, $memberId)
    {
        // Initialize gateways
        $this->initializeGateways($paymentMethods);

        // Execute payment gateway pay method
        $returnValue = $this->primaryGateway->pay($validatedCart, $memberId);

        return $returnValue;
    }

    public function postBack($paymentMethods, $validatedCart, $memberId, $params=[])
    {
        // Initialize gateways
        $this->initializeGateways($paymentMethods);

        // Execute payment gateway postback method
        if($validatedCart === null && $memberId === null){
            $returnValue = $this->primaryGateway->postBackMethod($params);
        }
        else{
            $returnValue = $this->primaryGateway->postBackMethod($validatedCart, $memberId, $params);
        }

        return $returnValue;
    }

    public function returnMethod($paymentMethods, $params=[])
    {
        // Initialize gateways
        $this->initializeGateways($paymentMethods);

        // Execute payment gateway return method
        $returnValue = $this->primaryGateway->returnMethod($params);

        return $returnValue;
    }

    public function getPointGateway()
    {
        return $this->pointGateway;
    }

    public function getPrimaryGateway()
    {
        return $this->primaryGateway;
    }

    /**
     * Check if payment method is accepts points deduction
     * @param  string  $paymentMethodString
     * @return boolean
     */
    public function isPaymentMethodAcceptPoints($paymentMethodString)
    {
        $configLoad = $this->paymentConfig;
        if(isset($configLoad['payment_type'][strtolower($paymentMethodString)]) 
            && isset($configLoad['payment_type'][strtolower($paymentMethodString)]['Easyshop']['points'])){  
            return $configLoad['payment_type'][strtolower($paymentMethodString)]['Easyshop']['points'];
        }

        return false;
    }

    /**
     * Get order points spent in transaction
     * @param  mixed $orderArgument
     * @return integer
     */
    public function getTransactionPoints($orderArgument)
    {
        if(is_numeric($orderArgument)){
            $orderId = $orderArgument;
        }
        else if(is_object($orderArgument)){
            $orderId = $orderArgument->getIdOrder();
        }
        else{
            return 0;
        }

        $orderPoints = $this->em->getRepository('EasyShop\Entities\EsPaymentGateway')
                                ->findOneBy([
                                    'order' => $orderId,
                                    'paymentMethod' => EsPaymentMethod::PAYMENT_POINTS
                                ]);

        if($orderPoints){
            return (float) $orderPoints->getAmount();
        }
        else{
            return 0;
        }
    }

    /**
     * Revert point transaction
     * @param  integer $orderId
     * @return boolean
     */
    public function revertTransactionPoint($orderId)
    {
        $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                          ->find($orderId);

        if($order){
            $points = $this->getTransactionPoints($order);
            $memberId = $order->getBuyer()->getIdMember();
            if((int)$points > 0){
                $this->pointTracker->addUserPoint(
                    $memberId,
                    EsPointType::TYPE_REVERT, 
                    false, 
                    $points
                );
            }

            return true;
        }

        return false;
    }

    /**
     * Send email and sms notification after payment completed
     * @param  integer $orderId 
     * @param  integer $memberId 
     * @param  boolean $buyer
     * @param  boolean $seller
     * @return boolean
     */
    public function sendPaymentNotification($orderId, $sendBuyer = true, $sendSeller = true)
    {
        $imageArray = $this->configLoader->getItem('email', 'images'); 
        $xmlfile =  $this->xmlResourceService->getContentXMLfile();
        $sender = $this->xmlResourceService->getXMlContent($xmlfile, 'message-sender-id', "select");
        $messageSender = $this->em->find('EasyShop\Entities\EsMember', (int)$sender);
         
        $orderProducts = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                  ->findBy(['order' => $orderId ]);
                                                  
        $buyer = $orderProducts[0]->getOrder()->getBuyer();
        $order = $orderProducts[0]->getOrder();

        $buyerAddress  = $this->em->getRepository('EasyShop\Entities\EsAddress')
                                  ->findOneBy(['idMember' => $buyer->getIdMember(), 'type' => EsAddress::TYPE_DELIVERY]);


        switch($order->getPaymentMethod()->getIdPaymentMethod()){
            case EsPaymentMethod::PAYMENT_PAYPAL:
                $messageBuyer = $this->languageLoader->getLine('payment_paypal_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_ppdp_seller');
                $paymentString = "PayPal";
                break;
            case EsPaymentMethod::PAYMENT_DRAGONPAY:
                $messageBuyer = $this->languageLoader->getLine('payment_dp_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_ppdp_seller');
                $paymentString = "DragonPay";
                break;
            case EsPaymentMethod::PAYMENT_CASHONDELIVERY:
                $messageBuyer = $this->languageLoader->getLine('payment_cod_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_cod_seller');
                $paymentString = "Cash on Delivery";
                break;
            case EsPaymentMethod::PAYMENT_PESOPAYCC:
                $messageBuyer = $this->languageLoader->getLine('payment_pesopay_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_ppdp_seller');
                $paymentString = "Pesopay Credit/Debit Card";
                break;
            case EsPaymentMethod::PAYMENT_POINTS:
                $messageBuyer = $this->languageLoader->getLine('payment_cod_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_cod_seller');
                $paymentString = "Easy Points";
                break;
        }


        $socialMediaLinks = $this->socialMediaManager->getSocialMediaLinks(); 
        $dataBuyer = [
            'id_order' => $order->getIdOrder(),
            'dateadded' => $order->getDateadded()->format('Y-m-d'),
            'buyer_name' => $buyer->getUserName(),
            'buyer_slug' => $buyer->getSlug(),  
            'totalprice' => $order->getTotal(),
            'invoice_no' => $order->getInvoiceNo(), 
            'buyer_store' => $buyer->getStoreName(),
            'facebook' => $socialMediaLinks["facebook"],
            'twitter' => $socialMediaLinks["twitter"],
            'payment_msg_buyer' => $messageBuyer,
            'products' => [],
            'baseUrl' => base_url(),
            'payment_method_name' => $paymentString,
        ];

        $dataArraySeller = [];

        foreach ($orderProducts as $orderProduct) {
            $seller = $orderProduct->getSeller();
            $sellerId = $seller->getIdMember();
            $orderProductId = $orderProduct->getIdOrderProduct();
            $product = $orderProduct->getProduct();
            $productAttr = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr')
                                    ->findBy(['orderProduct' => $orderProductId]);

            $attrArray = [];
            foreach ($productAttr as $attr) {
                $attrArray[] = [
                    'attr_name' => $attr->getAttrName(),
                    'attr_value' => $attr->getAttrValue(),
                ];
            }
            
            /**
             * Retrieve product image
             */
            $primaryImage = $this->em->getRepository('EasyShop\Entities\EsProductImage')
                                 ->getDefaultImage($product->getIdProduct());
            $imagePath = $primaryImage->getDirectory().'categoryview/'.$primaryImage->getFilename();
            $imagePath = ltrim($imagePath, '.');
            if(strtolower(ENVIRONMENT) === 'development'){
                $imagePath = $imagePath[0] !== '/' ? '/'.$imagePath : $imagePath;
                $imageArray[] = $imagePath;
                $parsedImage = $primaryImage->getFilename();
            }
            else{
                $parsedImage = getAssetsDomain().ltrim($imagePath, '/');
            }
            
            if(!isset($dataBuyer['products'][$orderProductId])){ 
                $arrayCollection = [
                    'order_product_id' => $orderProductId,
                    'seller_slug' => $seller->getSlug(),
                    'seller_store' => $seller->getStoreName(),
                    'buyer_store' => $buyer->getStoreName(), 
                    'name' => $product->getName(),
                    'baseprice' => number_format($orderProduct->getPrice(), 2, '.', ','),
                    'order_quantity' => $orderProduct->getOrderQuantity(),
                    'handling_fee' => number_format($orderProduct->getHandlingFee(), 2, '.', ','),
                    'finalprice' => number_format($orderProduct->getTotal(), 2, '.', ','),
                    'attr' => $attrArray,
                    'primaryImage' => $parsedImage,
                    'productLink' => 'item/'.$product->getSlug(),
                ];
                $dataBuyer['products'][$orderProductId] = $arrayCollection; 
            }

            if(!isset($dataArraySeller[$sellerId])){
                $dataArraySeller[$sellerId] = [
                    'seller' => $seller,
                    'seller_email' => $seller->getEmail(),
                    'seller_store' => $seller->getStoreName(),
                    'seller_contactno' => $seller->getContactno(),
                    'buyer_store' => $buyer->getStoreName(),
                    'store_link' => base_url(),
                    'payment_msg_seller' => $messageSeller,
                    'payment_method_name' => $paymentString,
                    'invoice_no' => $order->getInvoiceNo(),
                    'stateregion' => $buyerAddress->getStateregion()->getLocation(),
                    'city' => $buyerAddress->getCity()->getLocation(), 
                    'dateadded' => $order->getDateadded()->format('Y-m-d'),
                    'address' => $buyerAddress->getAddress(),
                    'buyer_contactno' => strlen(trim($buyerAddress->getMobile())) > 0 ? $buyerAddress->getMobile() : "N/A",
                    'buyer_telephone' => strlen(trim($buyerAddress->getTelephone()))  > 0 ? $buyerAddress->getTelephone() : "N/A",
                    'facebook' => $socialMediaLinks["facebook"],
                    'twitter' => $socialMediaLinks["twitter"],
                ];
            }

            if(!isset($dataArraySeller[$sellerId]['products'][$orderProductId])){
                $arrayCollection = [
                    'order_product_id' => $orderProductId,  
                    'name' => $product->getName(),
                    'baseprice' => number_format($orderProduct->getPrice(), 2, '.', ','),
                    'order_quantity' => $orderProduct->getOrderQuantity(),
                    'handling_fee' => number_format($orderProduct->getHandlingFee(), 2, '.', ','),
                    'finalprice' => number_format($orderProduct->getTotal(), 2, '.', ','),
                    'easyshop_charge' => number_format($orderProduct->getEasyshopCharge(), 2, '.', ','),
                    'payment_method_charge' => number_format($orderProduct->getPaymentMethodCharge(), 2, '.', ','),
                    'attr' => $attrArray,
                    'net' => number_format($orderProduct->getNet(), 2, '.', ','),
                    'primaryImage' => $parsedImage,
                    'productLink' => 'item/'.$product->getSlug(),
                ];

                $dataArraySeller[$sellerId]['products'][$orderProductId] = $arrayCollection;
            } 
        }

        if($sendBuyer){ 
        
            $pointsSpent = $this->getTransactionPoints($orderId);
            /**
             * Work around for Codeigniter's templating engine lack of support
             * for conditionals: use arrays
             */
            $dataBuyer['pointsSpent'] = [];
            $dataBuyer['totalLessPoint'] = [];
            if($pointsSpent > 0){
                $dataBuyer['pointSpent'][] = [ 'value' => $pointsSpent ];
                $dataBuyer['totalLessPoint'][] = [ 'value' => bcsub($order->getTotal(), $pointsSpent, 4) ]; 
            }

            $buyerMsg = $this->parserLibrary->parse('emails/email_purchase_notification_buyer', $dataBuyer, true);
            $buyerSubject = $this->languageLoader->getLine('notification_subject_buyer');
            $buyerSmsMsg = $buyer->getStoreName() . $this->languageLoader->getLine('notification_txtmsg_buyer');

            $this->emailService->setRecipient($buyer->getEmail())
                               ->setSubject($buyerSubject)
                               ->setMessage($buyerMsg, $imageArray)
                               ->queueMail();

            $this->smsService->setMobile($buyer->getContactno())
                             ->setMessage($buyerSmsMsg)
                             ->queueSMS();

            if($messageSender){
                $this->messageManager->sendMessage($messageSender, $buyer, $this->languageLoader->getLine('message_to_buyer'));
            }
        }

        if($sendSeller){
            foreach ($dataArraySeller as $key => $value) {
                $sellerEmail = $value['seller_email'];
                $sellerContact = $value['seller_contactno'];
                $seller = $value['seller'];
                unset($value['seller']);
                $value['baseUrl'] = base_url();

                $sellerSubject = $this->languageLoader->getLine('notification_subject_seller');
                $sellerMsg = $this->parserLibrary->parse('emails/email_purchase_notification_seller', $value, true);
                $sellerSmsMsg = $value['seller_store'] . $this->languageLoader->getLine('notification_txtmsg_seller');

                $this->emailService->setRecipient($sellerEmail)
                                   ->setSubject($sellerSubject)
                                   ->setMessage($sellerMsg, $imageArray)
                                   ->queueMail();
                
                $this->smsService->setMobile($sellerContact)
                                 ->setMessage($sellerSmsMsg)
                                 ->queueSMS();

                if($messageSender){
                    $this->messageManager->sendMessage($messageSender, $seller, $this->languageLoader->getLine('message_to_seller'));
                }
            }
        } 
    }

    /**
     * Check if ip address in postback url
     * @param  string  $ipAddress
     * @param  integer $paymentType
     * @return boolean
     */
    public function checkIpIsValidForPostback($ipAddress, $paymentType)
    {
        $config = $this->paymentConfig['payment_type']; 
        $configPayment = null;
        switch($paymentType){ 
            case EsPaymentMethod::PAYMENT_DRAGONPAY: 
                $configPayment = $config['dragonpay']['Easyshop'];
                break; 
            case EsPaymentMethod::PAYMENT_PESOPAYCC:
                $configPayment = $config['pesopay']['Easyshop'];
                break;
        }

        // temporarily disable for dragonpay
        if((int)$paymentType === EsPaymentMethod::PAYMENT_DRAGONPAY){
            return true;
        }

        if($configPayment){
            $ipList = isset($configPayment['ip_address']) 
                      ? $configPayment['ip_address']
                      : [];
            $ipRange = isset($configPayment['range_ip']) 
                      ? $configPayment['range_ip']
                      : []; 

            if(empty($ipList) === false){
                if(in_array($ipAddress, $ipList)){
                    return true;
                } 
            }

            if(empty($ipRange) === false){
                foreach ($ipRange as $range) {
                    $lowIp = ip2long($range[0]);
                    $highIp = ip2long($range[1]);
                    $longIpAddress = ip2long($ipAddress);
                    if ($longIpAddress <= $highIp 
                        && $lowIp <= $longIpAddress) {
                        return true; 
                    }
                }
            }
        }

        return false;
    }
}

