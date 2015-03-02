<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentGateway;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\PaymentService\PaymentService as PaymentService;


/**
 * Cash On Delivery Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 *
 *
 * Params needed
 *      method:"CashOnDelivery" 
 */
class CashOnDeliveryGateway extends AbstractGateway
{

    private $paymentType;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);
    }

    /**
     * Pay method for Cash On Delivery Gateway Class
     * 
     */
    public function pay($validatedCart, $memberId, $paymentService)
    {
        // Set status response
        $response['status'] = PaymentService::STATUS_FAIL;
        
        // Point Gateway
        $pointGateway = $paymentService->getPointGateway();
        $response['paymentType'] = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        $response['textType'] = 'cashondelivery';
        $response['message'] = 'Your payment has been completed through Cash on Delivery.';

        $this->setParameter('paymentType', $response['paymentType']);
        $productCount = count($validatedCart['itemArray']);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getShippingAddress(intval($memberId));

        // Compute shipping fee
        $prepareData = $paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));
        $grandTotal = $prepareData['totalPrice'];
        $this->setParameter('amount', $grandTotal);
        $productString = $prepareData['productstring'];
        $itemList = $prepareData['newItemList']; 
        $txnid = $this->generateReferenceNumber($memberId);
        $response['txnid'] = $txnid;

        if($validatedCart['itemCount'] === $productCount){
            $return = $paymentService->persistPayment(
                $grandTotal, 
                $memberId, 
                $productString, 
                $productCount, 
                json_encode($itemList),
                $txnid,
                $this
            );

            if($return['o_success'] <= 0){
                $response['message'] = $return['o_message'];
            }
            else{
                $response['orderId'] = $v_order_id = $return['v_order_id'];
                $response['invoice'] = $invoice = $return['invoice_no'];
                $response['status'] = PaymentService::STATUS_SUCCESS;

                foreach ($itemList as $key => $value) {  
                    $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                }

                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                  ->find($v_order_id);

                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                          ->find($this->getParameter('paymentType'));

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount($this->getParameter('amount'));
                $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentRecord->setOrder($order);
                $paymentRecord->setPaymentMethod($paymentMethod);
                
                $this->em->persist($paymentRecord);

                if($pointGateway !== null){
                    $pointGateway->setParameter('memberId', $memberId);
                    $pointGateway->setParameter('itemArray', $return['item_array']);

                    $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                              ->find($pointGateway->getParameter('paymentType'));

                    $trueAmount = $pointGateway->pay();

                    $pointRecord = new EsPaymentGateway();
                    $pointRecord->setAmount($trueAmount);
                    $pointRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                    $pointRecord->setOrder($order);
                    $pointRecord->setPaymentMethod($paymentMethod);

                    $this->em->persist($pointRecord);   
                }
                $this->em->flush();
            }
        }
        else{
            $response['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
        }
        return $response;
    }

    public function getExternalCharge()
    {
        return 0;
    }

    public function generateReferenceNumber($memberId)
    {
        return 'COD-'.date('ymdhs').'-'.$memberId;
    }

    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }

    public function getOrderProductStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }

}

