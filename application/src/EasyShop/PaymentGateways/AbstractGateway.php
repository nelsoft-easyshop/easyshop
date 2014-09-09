<?php

namespace EasyShop\PaymentGateways;

use Doctrine\ORM\Query\ResultSetMapping;
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
use \DateTime;


/**
 * Base payment gateway class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
abstract class AbstractGateway implements GatewayInterface
{
    /**
     * @var int
     */
    protected $amountAllocated;

    /**
     * @var string
     */
    protected $paymentMethodName;

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
     * @var int
     */
    protected $PayMentPayPal = 1;


    /**
     * @var int
     */
    protected $PayMentDragonPay = 2;

    /**
     * @var int
     */
    protected $PayMentCashOnDelivery = 3;

    /**
     * @var int
     */
    protected $PayMentPesoPayCC = 4;

    /**
     * @var int
     */
    protected $PayMentDirectBankDeposit = 5;

    /**
     * Constructor
     * 
     */
    protected function __construct($params = [])
    {
        $this->em = $params['em'];
        $this->request = $params['request'];
        $this->pointTracker = $params['pointTracker'];
        $this->setParameters($params);
        $this->paymentMethodName = $this->parameters['method'];
        $this->amountAllocated = $this->parameters['amount'];
    }

    /**
     * Pay Method
     */
    abstract public function pay();


    /**
     * Getters and Setters
     */
    public function getPaymentMethodName()
    {
        return $this->paymentMethodName;
    }

    public function getAmountAllocated()
    {
        return $this->amountAllocated;
    }

    public function setAmountAllocated($newAmount)
    {
        $this->amountAllocated = $newAmount;
    }

    public function setParameters($param = [])
    {
        foreach ($param as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    public function getParameter($key)
    {
        return $this->parameters[$key];
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
    public function persistPayment($paymentType,$ItemTotalPrice,$member_id,$productstring,$productCount,$apiResponse,$tid)
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

            switch ($paymentType) {
                case '1':
                    $orderStatus = EsOrderStatus::STATUS_DRAFT;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = ($totalAmount * 0.044) + 15;
                    break;
                case '2':
                    $orderStatus = EsOrderStatus::STATUS_DRAFT;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = EsOrderStatus::STATUS_VOID;
                    break;
                case '3':
                    $orderStatus = EsOrderStatus::STATUS_PAID;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = EsOrderStatus::STATUS_PAID;
                    break;
                case '4':
                    $orderStatus = EsOrderStatus::STATUS_DRAFT;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = EsOrderStatus::STATUS_VOID;
                    break;
                case '5':
                    $orderStatus = EsOrderStatus::STATUS_DRAFT;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = EsOrderStatus::STATUS_PAID;
                    break;
                default:
                    $orderStatus = EsOrderStatus::STATUS_PAID;
                    $orderProductStatus = EsOrderStatus::STATUS_PAID;
                    $externalCharge = EsOrderStatus::STATUS_PAID;
                    break;
            }
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


    /**
     * Generate Reference Number
     * 
     */
    public function validateCheckoutItems()
    {
        return true;
        $itemArray = $this->cartInstance->getCartCheckout();
        $qtysuccess = 0;

        foreach ($itemArray as $key => $value) {
            
            $productId = $value['id'];
            $itemId = $value['product_itemID'];


            $rsm = new ResultSetMapping();
            $rsm->addEntityResult('EasyShop\Entities\EsProduct','u');
            $rsm->addFieldResult('u','id_product','idProduct');

            $query = $this->em->createNativeQuery('SELECT id_product FROM `es_product` WHERE id_product = 1',$rsm);

            $users = $query->getResult();

            echo "<pre>"; var_dump($users); echo "</pre>";

            /*
            $rsm = new ResultSetMapping();
            $rsm->addEntityResult('EasyShop\Entities\EsProduct','u');
            $rsm->addFieldResult('u','id_product','idProduct');

            $query = $this->em->createNativeQuery('SELECT id_product FROM `es_product` WHERE id_product = 1',$rsm);

            $users = $query->getResult();

            echo "<pre>"; var_dump($users); echo "</pre>";

            <select id="getProductById">
            SELECT a.`name` AS `product`, a.`slug` AS slug, a.is_sold_out, a.`discount`, a.`brief`,a.`id_product`,`cat_id`, b.`product_image_path`, a.`price`, a.`member_id` as sellerid , a.`is_promote`,a.`startdate`,a.`enddate`, a.`promo_type`,a.`is_draft`,a.`is_delete`
            FROM `es_product` a
            LEFT JOIN `es_product_image` b ON b.`product_id` = a.`id_product` AND b.is_primary = '1'
            WHERE `id_product` = :id
            </select>
            */
        }
    }


    /**
     * Generate Reference Number
     * 
     * @param int $paymentType Specifies payment method
     * @param string $member_id Contains member id
     *
     * @return string
     */
    public function generateReferenceNumber($paymentType,$member_id){
    
        switch($paymentType)
        {
            case 1:
                $paycode = 'PPL';
                break;
            case 2:
                $paycode = 'DPY';
                break;
            case 3:
                $paycode = 'COD';
                break;
            case 4:
                $paycode = 'PPY';
                break;
            case 5:
                $paycode = 'DBP';
                break;
            default:
                $paycode = 'COD';
       }

       return $paycode.'-'.date('ymdhs').'-'.$member_id;
    }


}
