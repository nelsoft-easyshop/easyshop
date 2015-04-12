<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsOrderShippingAddress as EsOrderShippingAddress; 
use EasyShop\Entities\EsOrder as EsOrder; 
use EasyShop\Entities\EsOrderHistory as EsOrderHistory;
use EasyShop\Entities\EsOrderProduct as EsOrderProduct; 
use EasyShop\Entities\EsOrderBillingInfo as EsOrderBillingInfo; 
use EasyShop\Entities\EsOrderProductAttr as EsOrderProductAttr;
use EasyShop\Entities\EsOrderProductHistory as EsOrderProductHistory; 

/**
 * Base payment gateway class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
abstract class AbstractGateway implements GatewayInterface
{
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
     * @var mixed
     */
    protected $parameters;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Http foundation Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * PointTracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    protected $pointTracker;

    /**
     * Payment Service instance
     *
     * @var EasyShop\PaymentService\PaymentService
     */
    protected $paymentService;

    /**
     * Constructor
     * 
     */
    protected function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
        $this->paymentService = $paymentService;
        $this->setParameters($params);
    }

    /**
     * Abstract Methods
     */

    abstract public function pay($validatedCart, $memberId);
    abstract public function getExternalCharge();
    abstract public function getOrderStatus();
    abstract public function getOrderProductStatus();
    abstract public function generateReferenceNumber($memberId);
    
    /**
     * Getters and Setters
     */
    
    public function setParameters($param = [])
    {
        foreach ($param as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    public function setParameter($key, $value)
    {
         $this->parameters[$key] = $value;
    }

    public function getParameter($key)
    {
        return $this->parameters[$key];
    }

    public function showParameters()
    {
        return $this->parameters;
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
    protected function persistPayment($ItemTotalPrice, $member_id, $productstring, $productCount, $apiResponse, $tid, $gatewayReference)
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

                    if($billingInfo){
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

                $data["order_product_id"] = $orderProduct->getIdOrderProduct();
                $data["item_total_price"] = $details[5];
                $data["quantity"] = (int)$details[2];
                $itemArr[] = $data; 

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
        }
        catch(Exception $e){
            $this->em->getConnection()->rollback();
        }

        return $response;
    }
}
