<?php

namespace EasyShop\PaymentService;

use EasyShop\Entities\EsAddress;
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
use \DateTime;

/**
 * Payment Service Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PaymentService
{
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
    private $productManager;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $promoManager, $productManager)
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
        $this->promoManager = $promoManager;
        $this->productManager = $productManager;
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $paymentMethods Parameters for each gateway
     */
    public function initializeGateways($paymentMethods)
    {
        // Search array for point gateway
        $point = NULL;
        foreach (array_keys($paymentMethods) as $key) {
            if(strpos(strtolower($key), 'point') !== false){
                $point = $key;
                break;
            }
        }

        if($point !== NULL){
                $this->pointGateway = new \EasyShop\PaymentGateways\PointGateway(
                    $this->em,
                    $this->request,
                    $this->pointTracker,
                    $this,
                    $paymentMethods[$point]
                    );
                unset($paymentMethods[$point]);
        }

        // Retrieve Primary gateway
        $primaryGatewayValues = reset($paymentMethods);
        $primaryGatewayKey = key($paymentMethods);
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
     * Payment Order
     * 
     * @param int $paymentType Specifies payment method (included in gateway)
     * @param double $ItemTotalPrice Contains total price of items
     * @param string $member_id Contains member id
     * @param string $productstring Contains product descriptions
     * @param int $productCount Contains total count of products
     * @param string $apiResponse Contains response of api
     * @param string $tid Transaction id
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
        

        // start transaction
        $this->em->getConnection()->beginTransaction();

        $response['o_success'] = false;
        $response['o_message'] = 'Error Code: Payment000';
        
        try{
            $response['o_message'] = 'Error Code: Payment001';

            $addr = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->findOneBy([
                        'type' => 1,
                        'idMember' => $memberId
                        ]);

            $response['o_message'] = 'Error Code: Payment001.1';
            $locationLookupCity = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getCity()]);

            $locationLookupStateRegion = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getStateregion()]);

            $locationLookupCountry = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getCountry()]);
            
            $response['o_message'] = 'Error Code: Payment001.2';
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
            $response['o_message'] = 'Error Code: Payment002';

            $orderStatus = $gatewayReference->getOrderStatus();
            $orderProductStatus = $gatewayReference->getOrderProductStatus();
            $externalCharge = $gatewayReference->getExternalCharge();

            $response['o_message'] = 'Error Code: Payment002.1';
            $net = $totalAmount - $externalCharge;

            $buyer = $this->em->getRepository('EasyShop\Entities\EsMember')
                                    ->findOneBy(['idMember' => $memberId]);

            $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                            ->find($gatewayReference->getPaymentType());

            $orderStatusObj = $this->em->getRepository('EasyShop\Entities\EsOrderStatus')
                                            ->findOneBy(['orderStatus' => $orderStatus]); 

            $order = new EsOrder();
            $order->setInvoiceNo($invoiceNo);
            $order->setBuyer($buyer);
            $order->setTotal($totalAmount);
            $order->setDateadded(new DateTime("now"));
            $order->setDatemodified(new DateTime("now"));
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
            
            
            $response['o_message'] = 'Error Code: [HISTORY]Payment003';
            $orderHistory = new EsOrderHistory();
            $orderHistory->setOrder($order);
            $orderHistory->setComment("CREATED");
            $orderHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
            $orderHistory->setOrderStatus($orderStatusObj);
            $this->em->persist($orderHistory);
            $this->em->flush();

            $response['o_message'] = 'Error Code: Payment003.1';
            $order->setInvoiceNo($order->getIdOrder().'-'.$invoiceNo);
            $this->em->flush();

            $response['o_message'] = 'Error Code: Payment004';
            $productCounter = 1;
            $response['o_message'] = 'Error Code: Payment005';
            $products = explode('<||>', $productString);
            $response['o_message'] = 'Error Code: Payment006';
            foreach ($products as $product) {

                $details = explode('{+}', $product);
                $productExternalCharge = (floatval($details[5])/$totalAmount) * $externalCharge;
                $response['o_message'] = 'Error Code: Payment007a';
                

                $prod = $this->em->getRepository('EasyShop\Entities\EsProduct')
                                        ->find((int)$details[1]);

                $billingInfoId = $prod->getBillingInfoId();

                $response['o_message'] = 'Error Code: Payment008c';
                if($billingInfoId != 0){
                    
                    $billingInfo = $this->em->getRepository('EasyShop\Entities\EsBillingInfo')
                                                ->find($prod->getBillingInfoId());

                    $bankInfo = $this->em->getRepository('EasyShop\Entities\EsBankInfo')
                                                ->find($billingInfo->getBankId());
                    
                    $orderBillingInfo = new EsOrderBillingInfo();
                    $orderBillingInfo->setBankName($bankInfo->getBankName());
                    $orderBillingInfo->setAccountName($billingInfo->getBankAccountName());
                    $orderBillingInfo->setAccountNumber($billingInfo->getBankAccountNumber());
                    $this->em->persist($orderBillingInfo);
                    $this->em->flush();                    
                }

                $response['o_message'] = 'Error Code: Payment007b';
                $net = floatval($details[5]) - $productExternalCharge;

                $seller = $this->em->getRepository('EasyShop\Entities\EsMember')
                                        ->find((int)$details[0]);

                $ordProdStatus = $this->em->getRepository('EasyShop\Entities\EsOrderProductStatus')
                                            ->find($orderProductStatus);

                $response['o_message'] = 'Error Code: Payment008';
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
                $orderProduct->setSellerBillingId($orderBillingInfo->getIdOrderBillingInfo());
                $this->em->persist($orderProduct);
                $this->em->flush();
                
                $response['o_message'] = 'Error Code: Payment008a';

                if((int)$details[7] > 0){
                    $response['o_message'] = 'Error Code: Payment008b';
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

                $response['o_message'] = 'Error Code: Payment009';
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
        } catch(Exception $e){
            $this->em->getConnection()->rollback();
        }
        return $response;
    }

    /**
     * Computes Shipping Fee and Reorganizes Data (processData)
     * 
     * @param mixed $itemList List of items to compute shipping fee
     * @param string $address Used for shipping fee calcl
     *
     * @return mixed
     */
    public function computeFeeAndParseData($itemList,$address)
    {
        $city = ($address > 0 ? $address :  0);
        $cityDetails = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->getParentLocation($city);
        $region = $cityDetails->getParent();
        $cityDetails = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                    ->getParentLocation($region);
        $majorIsland = $cityDetails->getParent();

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
            /* TO BE IMPLEMENTED*/
            //$details = $this->payment_model->getShippingDetails($productId,$productItem,$city,$region,$majorIsland);
            //$shipping_amt = $details[0]['price'];
            $shipping_amt = 0.00;
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
            'thereIsPromote' => $promoItemCount
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
    function validateCartData($carts,$condition = FALSE)
    {
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
            $productInventoryDetail = $this->productManager->getProductInventory($productArray, false, $condition);
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

        // Set status response
        $response['status'] = 'f';
        $productCount = count($validatedCart['itemArray']);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                    ->getShippingAddress(intval($memberId));

        // Compute shipping fee
        $prepareData = $this->computeFeeAndParseData($validatedCart['itemArray'], intval($address));

        $grandTotal = $prepareData['totalPrice'];
        $productString = $prepareData['productstring'];
        $itemList = $prepareData['newItemList']; 

        $txnid = $this->primaryGateway->generateReferenceNumber($memberId);        
        $response['txnid'] = $txnid;
        if($validatedCart['itemCount'] === $productCount){
            $returnValue = $this->primaryGateway->pay();

            $return = $this->persistPayment(
                $grandTotal, 
                $memberId, 
                $productString, 
                $productCount, 
                json_encode($itemList),
                $txnid,
                $this->primaryGateway
                );

            if($return['o_success'] <= 0){
                 $returnValue['message'] = $return['o_message'];
            }
            else{
                $v_order_id = $return['v_order_id'];
                $invoice = $return['invoice_no'];
                $response['status'] = 's';

                foreach ($itemList as $key => $value) {  
                    $itemComplete = $this->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->productManager->updateSoldoutStatus($value['id']);
                }

                /* remove item from cart function */ 
                /* send notification function */ 
                /* Record PointGateway */
                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                            ->find($v_order_id);

                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                            ->find($this->primaryGateway->getPaymentType());

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount($this->primaryGateway->getParameter('amount'));
                $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentRecord->setOrder($order);
                $paymentRecord->setPaymentMethod($paymentMethod);

                $this->em->persist($paymentRecord);
                $this->em->flush();
            }
        }
        else{
            $returnValue['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
        }

        $response = array_merge($response, $returnValue);
        return $response;
    }
}