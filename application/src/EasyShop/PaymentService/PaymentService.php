<?php

namespace EasyShop\PaymentService;

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
     * Gateway instances holder
     * ['name' => obj]
     *
     * @var mixed
     */

    /**
     * Gateway return value holder
     * ['name' => returnval]
     *
     * @var mixed
     */
    private $returnValue = [];

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
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker)
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $breakdown Parameters for each gateway
     */
    public function setBreakdown($breakdown)
    {
        $services['em'] =  $this->em;
        $services['request'] =  $this->request;
        $services['pointTracker'] =  $this->pointTracker;
        $services['paymentService'] =  $this;
        foreach($breakdown as $breakdown){
            $path = $this->gatewayPath . "\\" . $breakdown["method"] . "Gateway";
            $obj = new $path(array_merge($breakdown,$services));
            $this->gateways[$breakdown["name"]] = $obj; 
            $this->returnValue[$breakdown["name"]] = NULL;
        }
    }


    /**
     * Returns all gateways being handled
     *
     * @return mixed
     */
    public function getAllGateways()
    {
        return $this->gateways;
    }

    /**
     * Returns a certain gateway
     *
     * @param string $name Name of gateway to be retrieved
     *
     * @return EasyShop\PaymentGateways\AbstractGateway
     */
    // returns a certain gateway
    public function getOneGateway($name)
    {
        return $this->gateways[$name];
    }

    /**
     * Deletes a certain gateway
     *
     * @param string $name Name of gateway to be deleted
     */
    public function deleteGateway($name)
    {
        unset($this->gateways[$name]);
    }

    public function getReturnValue($name)
    {
        return $this->returnValue[$name];
    }

    /**
     * Executes payment transaction for all registered gateways
     */
    public function pay()
    {

        foreach ($this->gateways as $gateway) {
            $this->returnValue[array_search($gateway, $this->gateways)] = $gateway->pay();
        }

    }

    /**
     * Payment Order
     * 
     * @param int $paymentType Specifies payment method
     * @param double $ItemTotalPrice Contains total price of items
     * @param string $member_id Contains member id
     * @param string $productstring Contains product descriptions
     * @param int $productCount Contains total count of products
     * @param string $apiResponse Contains response of api
     * @param string $tid Transaction id
     *
     * @return mixed
     */
    public function persistPayment($paymentType,$ItemTotalPrice,$member_id,$productstring,$productCount,$apiResponse,$tid, $gatewayReference)
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
            $locationLookup = $this->em->getRepository('EasyShop\Entities\EsLocationLookup')
                                        ->findOneBy(['idLocation' => $addr->getStateregion()]);
            
            $response['o_message'] = 'Error Code: Payment001.2';
            $shipOrderAddr = new EsOrderShippingAddress();
            $shipOrderAddr->setStateregion($locationLookup);
            $shipOrderAddr->setCity($locationLookup);
            $shipOrderAddr->setCountry($locationLookup);
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
                                            ->findOneBy(['idPaymentMethod' => $paymentType]);

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
                $orderProduct->setSellerBillingId($billingInfoId);
                $this->em->persist($orderProduct);
                $this->em->flush();
                $response['o_message'] = 'Error Code: Payment008c';

                if($billingInfoId != 0){
                    
                    $billingInfo = $this->em->getRepository('EasyShop\Entities\EsBillingInfo')
                                                ->find($prod->getBillingInfoId());

                    $bankInfo = $this->em->getRepository('EasyShop\Entities\EsBankInfo')
                                                ->find($billingInfo->getBankId());
                    
                    $orderBillingInfo = new EsOrderBillingInfo();
                    $orderBillingInfo->setOrderId($order->getIdOrder());
                    $orderBillingInfo->setorderProductId($orderProduct->getIdOrderProduct());
                    $orderBillingInfo->setBankName($bankInfo->getBankName());
                    $orderBillingInfo->setAccountName($billingInfo->getBankAccountName());
                    $orderBillingInfo->setAccountNumber($billingInfo->getBankAccountNumber());
                    $this->em->persist($orderBillingInfo);
                    $this->em->flush();                    
                }

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
                $response['o_message'] = 'Error Code: Payment009';
                $orderProductHistory = new EsOrderProductHistory();
                $orderProductHistory->setOrderProduct($orderProduct);
                }
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
    
}

