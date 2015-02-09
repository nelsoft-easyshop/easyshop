<?php

namespace EasyShop\PaymentService;

use EasyShop\Entities\EsAddress as EsAddress;
use EasyShop\Entities\EsOrderShippingAddress;
use EasyShop\Entities\EsLocationLookup;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus;
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
    /**
     * Error Codes
     *
     * @var mixed
     */
    private $error = [
        'init-failed' => [
            'code' => 'Error Code: Payment000', 
            'description' => 'Initialization failed'
            ],
        'EsAddress-repo-fail' => [
            'code' => 'Error Code: Payment001', 
            'description' => 'Failed to retrieve an address via repository'
            ],
        'EsLocationLookup-repo-fail' => [
            'code' => 'Error Code: Payment001.1', 
            'description' => 'Failed to retrieve multiple data from EsLocationLookup via repository'
            ],
        'EsOrderShippingAddress-failed-insert' => [
            'code' => 'Error Code: Payment001.2', 
            'description' => 'Failed to persist EsOrderShippingAddress object'
            ],
        'gateway-data-retrieval-fail' => [
            'code' => 'Error Code: Payment002', 
            'description' => 'Failed to retrieve gateway context data'
            ],
        'EsOrder-failed-insert' => [
            'code' => 'Error Code: Payment002.1', 
            'description' => 'Failed to persist EsOrder object'
            ],
        'EsOrderHistory-failed-insert' => [
            'code' => 'Error Code: [HISTORY]Payment003', 
            'description' => 'Failed to persist EsOrderHistory object'
            ],
        'EsOrder-failed-update' => [
            'code' => 'Error Code: Payment003.1', 
            'description' => 'Failed to update EsOrder Invoice No'
            ],
        'EsProduct-repo-failed' => [
            'code' => 'Error Code: Payment007a', 
            'description' => 'Failed to retrieve data from EsProduct repository'
            ],
        'EsOrderBillingInfo-failed-insert' => [
            'code' => 'Error Code: Payment008c', 
            'description' => 'Failed to persist EsOrderBillingInfo object'
            ],
        'EsMember-repo-fail' => [
            'code' => 'Error Code: Payment007b', 
            'description' => 'Failed to retrieve data from EsMember repository'
            ],
        'EsOrderProductStatus-repo-fail' => [
            'code' => 'Error Code: Payment007c', 
            'description' => 'Failed to retrieve data from EsOrderProductStatus repository'
            ],
        'EsOrderProduct-failed-insert' => [
            'code' => 'Error Code: Payment008', 
            'description' => 'Failed to persist EsOrderProduct object'
            ],
        'EsOrderProductAttr-failed-insert' => [
            'code' => 'Error Code: Payment008b', 
            'description' => 'Failed to persist EsOrderProductAttr object'
            ],
        'EsOrderProductHistory-failed-insert' => [
            'code' => 'Error Code: Payment009', 
            'description' => 'Failed to persist EsOrderProductHistory object'
            ]
    ];

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
    private $configLoader;

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
                                $messageManager)
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
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $paymentMethods Parameters for each gateway
     */
    public function initializeGateways($paymentMethods)
    {
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
     * Persist Payment
     * 
     * @param int $paymentType Specifies payment method (included in gateway)
     * @param double $ItemTotalPrice Contains total price of items
     * @param string $member_id Contains member id
     * @param string $productstring Contains product descriptions
     * @param int $productCount Contains total count of products
     * @param string $apiResponse Contains response of api
     * @param string $tid Transaction id
     *
     *
     * @return mixed
     */
    public function persistPayment($ItemTotalPrice,$member_id,$productstring,$productCount,$apiResponse,$tid, $gatewayReference)
    {
        // remap variables
        $invoiceNo = $member_id.'-'.date('ymdhs');
        $totalAmount = $ItemTotalPrice;
        $ip = $this->request->getClientIp();
        $memberId = $member_id;
        $productString = $productstring;
        $productCount = $productCount;
        $dataResponse = $apiResponse;
        $transactionId = $tid;
        
        // array for point gateway
        $itemArr = [];

        // start transaction
        $this->em->getConnection()->beginTransaction();

        $response['o_success'] = false;
        $response['o_message'] = $this->error['init-failed']['code'];
        
        try{
            $response['o_message'] = $this->error['EsAddress-repo-fail']['code'];

            $addr = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->findOneBy([
                        'type' => 1,
                        'idMember' => $memberId
                        ]);

            $response['o_message'] = $this->error['EsLocationLookup-repo-fail']['code'];
            $locationLookupCity = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getCity()]);

            $locationLookupStateRegion = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getStateregion()]);

            $locationLookupCountry = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getCountry()]);
            
            $response['o_message'] = $this->error['EsOrderShippingAddress-failed-insert']['code'];
            $shipOrderAddr = new EsOrderShippingAddress();
            $shipOrderAddr->setCity($locationLookupCity);
            $shipOrderAddr->setStateregion($locationLookupStateRegion);
            $shipOrderAddr->setCountry($locationLookupCountry);
            $shipOrderAddr->setAddress($addr->getAddress());
            $shipOrderAddr->setConsignee($addr->getConsignee());
            $shipOrderAddr->setMobile($addr->getMobile());
            $shipOrderAddr->setTelephone($addr->getTelephone());
            $shipOrderAddr->setLat($addr->getLat());
            $shipOrderAddr->setLng($addr->getLng());

            $this->em->persist($shipOrderAddr);
            $this->em->flush();

            $addrId = $shipOrderAddr->getIdOrderShippingAddress();
            $response['o_message'] = $this->error['gateway-data-retrieval-fail']['code'];

            $orderStatus = $gatewayReference->getOrderStatus();
            $orderProductStatus = $gatewayReference->getOrderProductStatus();
            $externalCharge = $gatewayReference->getExternalCharge();

            $response['o_message'] = $this->error['EsOrder-failed-insert']['code'];
            $net = $totalAmount - $externalCharge;

            $buyer = $this->em->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy(['idMember' => $memberId]);

            $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                            ->find($gatewayReference->getParameter('paymentType'));

            $orderStatusObj = $this->em->getRepository('EasyShop\Entities\EsOrderStatus')
                                            ->findOneBy(['orderStatus' => $orderStatus]); 
        
            
            $order = new EsOrder();
            $order->setInvoiceNo($invoiceNo);
            $order->setBuyer($buyer);
            $order->setTotal($totalAmount);
            $order->setDateadded(date_create(date("Y-m-d H:i:s")));
            $order->setDatemodified(date_create(date("Y-m-d H:i:s")));
            $order->setIp($ip);
            $order->setShippingAddressId($addrId);
            $order->setPaymentMethod($paymentMethod);
            $order->setOrderStatus($orderStatusObj);
            $order->setDataResponse($dataResponse);
            $order->setTransactionId($transactionId);
            $order->setPaymentMethodCharge($externalCharge);
            $order->setNet($net);
            $this->em->persist($order);
            $this->em->flush();
            

            $response['o_message'] = $this->error['EsOrderHistory-failed-insert']['code'];
            $orderHistory = new EsOrderHistory();
            $orderHistory->setOrder($order);
            $orderHistory->setComment("CREATED");
            $orderHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
            $orderHistory->setOrderStatus($orderStatusObj);
            $this->em->persist($orderHistory);
            $this->em->flush();


            $response['o_message'] = $this->error['EsOrder-failed-update']['code'];
            $order->setInvoiceNo($order->getIdOrder().'-'.$invoiceNo);
            $this->em->flush();

            $productCounter = 1;
            $products = explode('<||>', $productString);
            foreach ($products as $product) {

                $details = explode('{+}', $product);
                $productExternalCharge = (floatval($details[5])/$totalAmount) * $externalCharge;
                $response['o_message'] = $this->error['EsProduct-repo-failed']['code'];
                

                $prod = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->find((int)$details[1]);

                $billingInfoId = $prod->getBillingInfoId();

                $response['o_message'] = $this->error['EsOrderBillingInfo-failed-insert']['code'];
                if($billingInfoId != 0){
                    
                    $billingInfo = $this->em->getRepository('EasyShop\Entities\EsBillingInfo')
                                                ->find($prod->getBillingInfoId());

                    $bankInfo = $this->em->getRepository('EasyShop\Entities\EsBankInfo')
                                                ->find($billingInfo->getBankId());
                    
                    $orderBillingInfo = new EsOrderBillingInfo();
                    $orderBillingInfo->setBankName($bankInfo->getBankName());
                    $orderBillingInfo->setAccountName($billingInfo->getBankAccountName());
                    $orderBillingInfo->setAccountNumber($billingInfo->getBankAccountNumber());
                    $orderBillingInfo->setCreatedAt(date_create(date("Y-m-d H:i:s")));
                    $orderBillingInfo->setUpdatedAt(date_create(date("Y-m-d H:i:s")));
                    $this->em->persist($orderBillingInfo);
                    $this->em->flush();

                    $billingInfoId = $orderBillingInfo->getIdOrderBillingInfo();
                }

                $response['o_message'] = $this->error['EsMember-repo-fail']['code'];
                $net = floatval($details[5]) - $productExternalCharge;

                $seller = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find((int)$details[0]);

                $response['o_message'] = $this->error['EsOrderProductStatus-repo-fail']['code'];
                $ordProdStatus = $this->em->getRepository('EasyShop\Entities\EsOrderProductStatus')
                                            ->find($orderProductStatus);

                $response['o_message'] = $this->error['EsOrderProduct-failed-insert']['code'];
                $orderProduct = new EsOrderProduct();
                $orderProduct->setOrder($order);
                $orderProduct->setSeller($seller);
                $orderProduct->setProduct($prod);
                $orderProduct->setOrderQuantity((int)$details[2]);
                $orderProduct->setPrice($details[3]);
                $orderProduct->setHandlingFee($details[4]);
                $orderProduct->setTotal($details[5]);
                $orderProduct->setProductItemId((int)$details[6]);
                $orderProduct->setStatus($ordProdStatus);
                $orderProduct->setPaymentMethodCharge((string)$productExternalCharge);
                $orderProduct->setNet((string)$net);
                $orderProduct->setSellerBillingId($billingInfoId);
                $this->em->persist($orderProduct);
                $this->em->flush();

                for($x = 0; $x < (int)$details[2]; $x++){
                    $data["order_id"] = $orderProduct->getIdOrderProduct();
                    $data["point"] = $prod->getMaxAllowablePoint();
                    $itemArr[] = $data;
                }

                if((int)$details[7] > 0){
                    $response['o_message'] = $this->error['EsOrderProductAttr-failed-insert']['code'];
                    $attrString = explode('(-)', $details[8]);
                    foreach ($attrString as $attr) {
                        $attrsExplode = explode('[]', $attr);
                        $orderProductAttr = new EsOrderProductAttr();
                        $orderProductAttr->setOrderProduct($orderProduct);
                        $orderProductAttr->setAttrName($attrsExplode[0]);
                        $orderProductAttr->setAttrValue($attrsExplode[1]);
                        $orderProductAttr->setAttrPrice($attrsExplode[2]);
                        $this->em->persist($orderProductAttr);
                        $this->em->flush();  
                    }
                }

                
                $response['o_message'] = $this->error['EsOrderProductHistory-failed-insert']['code'];
                $orderProductHistory = new EsOrderProductHistory();
                $orderProductHistory->setOrderProduct($orderProduct);
                $orderProductHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $orderProductHistory->setOrderProductStatus($ordProdStatus);
                $this->em->persist($orderProductHistory);
                $this->em->flush();  
            }
            $response['o_message'] = 'Success! Transaction Saved';
            $response['o_success'] = true;
            
            $this->em->getConnection()->commit();


            $response['v_order_id'] = $order->getIdOrder();
            $response['invoice_no'] = $order->getInvoiceNo();
            $response['total'] = $order->getTotal();
            $response['dateadded'] = $order->getDateadded();
            $response['item_array'] = $itemArr;
        } catch(Exception $e){
            $this->em->getConnection()->rollback();
        }
        return $response;
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
            $tax_amt = 0;
            $promoItemCount = ($value['is_promote'] == 1) ? $promoItemCount += 1 : $promoItemCount += 0;
            $productItem =  $value['product_itemID'];
            
            $details = $this->em->getRepository('EasyShop\Entities\EsProductShippingDetail')
                            ->getShippingDetailsByLocation($productId,$productItem, $city, $region->getIdLocation(), $majorIsland->getIdLocation());

            $shipping_amt = (isset($details[0]['price'])) ? $details[0]['price'] : 0 ;
            
            $otherFee = ($tax_amt + $shipping_amt) * $orderQuantity;
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
     * @param mixed $carts User Session data
     * @param bool $condition Used for lock-related processing
     *
     * @return mixed
     */
    function validateCartData($carts, $pointsAllocated = "0.00", $excludeMemberId = 0)
    {
        $condition = true;
        $itemArray = $carts['choosen_items'];
        $availableItemCount = 0;
        $totalPointsAllowable = "0.00";

        foreach ($itemArray as $key => $value) {
            $prod = $this->em->getRepository('EasyShop\Entities\EsProduct')->find(intval($value['id']));
            $totalPointsAllowable = bcmul(bcadd($totalPointsAllowable, $prod->getMaxAllowablePoint()), $value['qty']);
        }

        if(intval($totalPointsAllowable) === 0){
            $totalPointsAllowable = "1.00";
            $pointsAllocated = "0.00";
        }
        else{
            $pointsAllocated = intval($pointsAllocated) <= intval($totalPointsAllowable) ? $pointsAllocated : $totalPointsAllowable;
        }

        foreach($itemArray as $key => $value){

            $productId = $value['id'];
            $itemId = $value['product_itemID'];

            $productArray = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                            ->find($productId);

            $pointDeductable = bcmul($pointsAllocated, bcdiv($productArray->getMaxAllowablePoint(), $totalPointsAllowable, 10), 10);

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
            $finalPromoPrice = round(floatval(bcsub($finalPromoPrice, $pointDeductable, 10)));
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
        $returnValue = $this->primaryGateway->pay($validatedCart, $memberId, $this);

        return $returnValue;
    }

    public function postBack($paymentMethods, $validatedCart, $memberId, $params=[])
    {
        // Initialize gateways
        $this->initializeGateways($paymentMethods);

        // Execute payment gateway postback method
        if($validatedCart === null && $memberId === null){
            $returnValue = $this->primaryGateway->postBackMethod($this, $params);
        }
        else{
            $returnValue = $this->primaryGateway->postBackMethod($validatedCart, $memberId, $this, $params);
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

    /**
     * Get payment method type per user
     * @param  integer $memberId
     * @return mixed
     */
    public function getUserPaymentMethod($memberId)
    {
        $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethodUser')
                                            ->findBy(['member'=>$memberId]);
        
        $paymentArray = [];
        $paymentArray['all'] = false;
        if($paymentMethod){
            foreach ($paymentMethod as $key => $value) {
                $paymentArray['payment_method'][] = $value->getPaymentMethod()->getIdPaymentMethod();
            }
        }
        else{
            $paymentArray['all'] = true;
        }

        return $paymentArray;
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
        $imageArray[] = "/assets/images/appbar.home.png";
        $imageArray[] = "/assets/images/appbar.message.png"; 
        $xmlfile =  $this->xmlResourceService->getContentXMLfile();
        $sender = $this->xmlResourceService->getXMlContent($xmlfile, 'message-sender-id', "select");
        $messageSender = $this->em->find('EasyShop\Entities\EsMember', (int)$sender);
         
        $orderProducts = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                                  ->findBy(['order'=>$orderId]);

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
            //case 4:
                $messageBuyer = $this->languageLoader->getLine('payment_dp_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_ppdp_seller');
                $paymentString = "DragonPay";
                break;
            case EsPaymentMethod::PAYMENT_CASHONDELIVERY:
                $messageBuyer = $this->languageLoader->getLine('payment_cod_buyer');
                $messageSeller = $this->languageLoader->getLine('payment_cod_seller');
                $paymentString = "Cash on Delivery";
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
            'store_link' => base_url(),
            'facebook' => $socialMediaLinks["facebook"],
            'twitter' => $socialMediaLinks["twitter"],
            'msg_link' => base_url() . "messages/#",
            'payment_msg_buyer' => $messageBuyer,
            'products' => [],
        ];

        $dataArraySeller = [];

        foreach ($orderProducts as $key => $valueProduct) {
            $seller = $valueProduct->getSeller();
            $sellerId = $seller->getIdMember();
            $orderProductId = $valueProduct->getIdOrderProduct();
            $product = $valueProduct->getProduct();
            $productAttr = $this->em->getRepository('EasyShop\Entities\EsOrderProductAttr')
                                    ->findBy(['orderProduct' => $orderProductId]);

            $attrArray = [];
            foreach ($productAttr as $attr) {
                $attrArray[] = [
                    'attr_name' => $attr->getAttrName(),
                    'attr_value' => $attr->getAttrValue(),
                ];
            }
            if(!isset($dataBuyer['products'][$orderProductId])){ 

                $arrayCollection = [
                    'order_product_id' => $orderProductId,
                    'seller_slug' => $seller->getSlug(),
                    'seller_store' => $seller->getStoreName(),
                    'buyer_store' => $buyer->getStoreName(), 
                    'name' => $product->getName(),
                    'baseprice' => number_format($valueProduct->getPrice(), 2, '.', ','),
                    'order_quantity' => $valueProduct->getOrderQuantity(),
                    'handling_fee' => number_format($valueProduct->getHandlingFee(), 2, '.', ','),
                    'finalprice' => number_format($valueProduct->getTotal(), 2, '.', ','),
                    'attr' => $attrArray,
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
                    'baseprice' => number_format($valueProduct->getPrice(), 2, '.', ','),
                    'order_quantity' => $valueProduct->getOrderQuantity(),
                    'handling_fee' => number_format($valueProduct->getHandlingFee(), 2, '.', ','),
                    'finalprice' => number_format($valueProduct->getTotal(), 2, '.', ','),
                    'easyshop_charge' => number_format($valueProduct->getEasyshopCharge(), 2, '.', ','),
                    'payment_method_charge' => number_format($valueProduct->getPaymentMethodCharge(), 2, '.', ','),
                    'attr' => $attrArray,
                    'net' => number_format($valueProduct->getNet(), 2, '.', ','),
                ];

                $dataArraySeller[$sellerId]['products'][$orderProductId] = $arrayCollection;
            } 
        }

        if($sendBuyer){ 
            $buyerMsg = $this->parserLibrary->parse('emails/email_purchase_notification_buyer', $dataBuyer, true);
            $buyerSubject = $this->languageLoader->getLine('notification_subject_buyer');
            $buyerSmsMsg = $buyer->getStoreName() . $this->languageLoader->getLine('notification_txtmsg_buyer');

            $this->emailService->setRecipient($buyer->getEmail())
                               ->setSubject($buyerSubject)
                               ->setMessage($buyerMsg, $imageArray)
                               ->sendMail();

            $this->smsService->setMobile($buyer->getContactno())
                             ->setMessage($buyerSmsMsg)
                             ->sendSms();

            if($messageSender){
                $this->messageManager->send($messageSender, $buyer, $this->languageLoader->getLine('message_to_buyer'));
            }
        }

        if($sendSeller){
            foreach ($dataArraySeller as $key => $value) {
                $sellerEmail = $value['seller_email'];
                $sellerContact = $value['seller_contactno'];
                $seller = $value['seller'];
                unset($value['seller']);

                $sellerSubject = $this->languageLoader->getLine('notification_subject_seller');
                $sellerMsg = $this->parserLibrary->parse('emails/email_purchase_notification_seller', $value, true);
                $sellerSmsMsg = $value['seller_store'] . $this->languageLoader->getLine('notification_txtmsg_seller');

                $this->emailService->setRecipient($sellerEmail)
                                   ->setSubject($sellerSubject)
                                   ->setMessage($sellerMsg, $imageArray)
                                   ->sendMail();
                
                $this->smsService->setMobile($sellerContact)
                                 ->setMessage($sellerSmsMsg)
                                 ->sendSms();

                if($messageSender){
                    $this->messageManager->send($messageSender, $seller, $this->languageLoader->getLine('message_to_seller'));
                }
            }
        } 
    }

}

