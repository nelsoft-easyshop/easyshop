<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentGateway;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;
use EasyShop\PaymentService\PaymentService as PaymentService;
use EasyShop\Entities\EsOrderProductStatus as EsOrderProductStatus;


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
    public function pay($validatedCart, $memberId)
    {
        // Set status response
        $response['status'] = PaymentService::STATUS_FAIL;
        $response['error'] = true;
        // Point Gateway
        $pointGateway = $this->paymentService->getPointGateway();

        $response['paymentType'] = EsPaymentMethod::PAYMENT_CASHONDELIVERY;
        $response['textType'] = 'cashondelivery';
        $response['message'] = 'Your payment has been completed through Cash on Delivery.';

        $this->setParameter('paymentType', $response['paymentType']);
        $productCount = count($validatedCart['itemArray']);

        // get address Id
        $address = $this->em->getRepository('EasyShop\Entities\EsAddress')
                            ->getAddressStateRegionId((int)$memberId);

        // Compute shipping fee
        $prepareData = $this->paymentService->computeFeeAndParseData($validatedCart['itemArray'], intval($address));
        $grandTotal = $prepareData['totalPrice'];
        $this->setParameter('amount', $grandTotal);
        $productString = $prepareData['productstring'];
        $itemList = $prepareData['newItemList']; 
        $txnid = $this->generateReferenceNumber($memberId);
        $response['txnid'] = $txnid;

        if($pointGateway){
            $checkPointValid = $pointGateway->isPointValid($memberId, $grandTotal);
            if(!$checkPointValid['valid']){
                $response['message'] = $checkPointValid['message'];
                return $response;
            }
        }

        if((int) $grandTotal < 0){
            $response['message'] = "Negative total value not available.";
            return $response;
        }

        if($this->paymentService->checkOutService->checkoutCanContinue($validatedCart['itemArray'], $response['paymentType']) === false){
            $response['message'] = "Payment is not available using Cash on Delivery.";
            return $response;
        }

        if($validatedCart['itemCount'] === $productCount){
            $return = $this->persistPayment(
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
                $response['orderId'] = $orderId = $return['v_order_id'];
                $response['invoice'] = $invoice = $return['invoice_no'];
                $response['status'] = PaymentService::STATUS_SUCCESS;
                $response['error'] = false;

                foreach ($itemList as $key => $value) {  
                    $itemComplete = $this->paymentService->productManager->deductProductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    $this->paymentService->productManager->updateSoldoutStatus($value['id']);
                }

                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                  ->find($orderId);
                $deductAmount = "0.00";

                if($pointGateway !== null){
                    $pointGateway->setParameter('memberId', $memberId);
                    $pointGateway->setParameter('itemArray', $return['item_array']);

                    $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                              ->find($pointGateway->getParameter('paymentType'));

                    $deductAmount = $pointGateway->usePoints();

                    $pointRecord = new EsPaymentGateway();
                    $pointRecord->setAmount($deductAmount);
                    $pointRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                    $pointRecord->setOrder($order);
                    $pointRecord->setPaymentMethod($paymentMethod);
                    $this->em->persist($pointRecord);   
                }

                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                          ->find($this->getParameter('paymentType'));

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount(bcsub($this->getParameter('amount'), $deductAmount));
                $paymentRecord->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentRecord->setOrder($order);
                $paymentRecord->setPaymentMethod($paymentMethod);
                $this->em->persist($paymentRecord);

                $this->paymentService->sendPaymentNotification($orderId);

                $this->em->flush();
            }
        }
        else{
            $response['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
        }
        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function getExternalCharge()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function generateReferenceNumber($memberId)
    {
        return 'COD-'.date('ymdhs').'-'.$memberId;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderStatus()
    {
        return EsOrderStatus::STATUS_PAID;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderProductStatus()
    {
        return EsOrderProductStatus::ON_GOING;
    }

}

