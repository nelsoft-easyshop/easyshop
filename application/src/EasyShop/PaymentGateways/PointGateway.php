<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPointHistory;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsPaymentGateway as EsPaymentGateway;
use EasyShop\Entities\EsPointType as EsPointType; 
use EasyShop\PaymentService\PaymentService as PaymentService;
use EasyShop\Entities\EsOrderStatus as EsOrderStatus;

/**
 * Point Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PointGateway extends AbstractGateway
{
    const MAX_POINT_ALLOWED = PHP_INT_MAX;

    const MIN_AMOUNT_ALLOWED = 1000;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        parent::__construct($em, $request, $pointTracker, $paymentService, $params);
        $this->setParameter('paymentType', EsPaymentMethod::PAYMENT_POINTS);
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

        $response['paymentType'] = EsPaymentMethod::PAYMENT_POINTS;
        $response['textType'] = 'easypoints';
        $response['message'] = 'Your payment has been completed through Easy Points.';

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

        $checkPointValid = $this->isPointValid($memberId, $grandTotal);
        if(!$checkPointValid['valid']){
            $response['message'] = $checkPointValid['message'];
            return $response;
        } 

        if($this->paymentService->checkOutService->checkoutCanContinue($validatedCart['itemArray'], $response['paymentType'], false) === false){
            $response['message'] = "Payment is not available using Easy Points.";
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

                $this->setParameter('memberId', $memberId);
                $this->setParameter('itemArray', $return['item_array']);
                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                          ->find($this->getParameter('paymentType'));

                $deductAmount = $this->usePoints();

                $paymentRecord = new EsPaymentGateway();
                $paymentRecord->setAmount($deductAmount);
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
     * Pay method for Point Gateway Class
     * 
     */
    public function usePoints($param1 = null, $param2 = null, $param3 = null)
    {
        $memberId = $this->parameters['memberId'];
        $itemArray = $this->parameters['itemArray'];

        // get id of action
        $actionId = $this->pointTracker->getActionId($this->parameters['pointtype']);

        $pointSpent =  $this->parameters['amount'];

        // if 'purchase', add items as JSON
        if(intval($actionId) === EsPointType::TYPE_PURCHASE){ 
            $maxPointAllowable = "0.000";
            $pointBreakdown = [];

            foreach ($itemArray as $item) {
                $maxPointAllowable = bcadd($maxPointAllowable, $item['point']);
            }

            // cap points with respect to total points of items
            $pointSpent = intval($pointSpent) <= intval($maxPointAllowable) ? $pointSpent : $maxPointAllowable;

            foreach ($itemArray as $item) {
                $data["order_product_id"] = $item['order_product_id'];
                $data["points"] = $this->getProductDeductPoint($item['point'], $maxPointAllowable);
                $pointBreakdown[] = $data;
            }

            $jsonData = json_encode($pointBreakdown);

            // update user points!
            $historyObj = $this->pointTracker->spendUserPoint(
                $memberId,
                $actionId,
                $pointSpent
                );

            // update history data field
            if($historyObj){
                $historyObj->setData($jsonData);
            }
            $this->em->flush();
        }
        
        return $pointSpent;
    }

    /**
     * Get point distribution in product
     * @param  float $productPrice
     * @param  float $total
     * @return float
     */
    public function getProductDeductPoint($productPrice, $total)
    {
        return (float)bcmul($this->getParameter('amount'), bcdiv($productPrice, $total, 4), 4);
    }

    /**
     * Check if point request is valid
     * @param  integer $memberId
     * @param  float   $totalPrice
     * @return mixed
     */
    public function isPointValid($memberId, $totalPrice)
    {   
        $pointAmount = $this->getParameter('amount');
        $returnValue['valid'] = false;
        if($this->pointTracker->getUserPoint($memberId) < $pointAmount){
            $returnValue['message'] = "You have insufficient points.";
        }
        elseif ((float) $totalPrice < self::MIN_AMOUNT_ALLOWED) {
            $returnValue['message'] = "We only accept minimum of ".self::MIN_AMOUNT_ALLOWED." total amount per transaction to accept points.";
        }
        elseif((float) $pointAmount > self::MAX_POINT_ALLOWED){
            $returnValue['message'] = "We only accept ".self::MAX_POINT_ALLOWED." points per transaction.";
        }
        elseif ((float) $pointAmount > $totalPrice) {
            $returnValue['message'] = "Points cannot be greater than total price.";
        }
        elseif ((int) $pointAmount < 0) {
            $returnValue['message'] = "Points cannot be negative.";
        }
        else{
            $returnValue['valid'] = true;
        }

        return $returnValue;
    }

    public function getExternalCharge(){
        return 0;
    }

    public function generateReferenceNumber($memberId)
    {
        return 'ESP-'.date('ymdhs').'-'.$memberId;
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

/*
    Params needed
        method:"Point", 
        amount:amount allocated,
        pointtype:type of point used
*/

