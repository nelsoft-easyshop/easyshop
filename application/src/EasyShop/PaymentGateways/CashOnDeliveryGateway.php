<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentGateway;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus;

/**
 * Cash On Delivery Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class CashOnDeliveryGateway extends AbstractGateway
{

    /**
     * Constructor
     * 
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
    }


    /**
     * Pay method for Cash On Delivery Gateway Class
     * 
     */
    public function pay()
    {
        $response['status'] = 'f';

        if($this->parameters['lastDigit'] == 2){
            $paymentType = $this->PayMentDirectBankDeposit;
            $response['textType'] = 'directbankdeposit';
            $response['message'] = 'Your payment has been completed through Direct Bank Deposit.';
        }
        else{
            $paymentType = $this->PayMentCashOnDelivery;
            $response['textType'] = 'cashondelivery';
            $response['message'] = 'Your payment has been completed through Cash on Delivery.';
        }

        $grandTotal = $this->parameters['prepareData']['totalPrice'];

        $this->parameters['amount'] = $grandTotal;

        $productstring = $this->parameters['prepareData']['productstring'];
        $itemList = $this->parameters['prepareData']['newItemList']; 
        $txnid = $this->generateReferenceNumber($this->parameters['member_id']);
        
        // stub functions
        //if($this->parameters['qtysuccess'] == $this->parameters['productCount']){
        if(true){
            $return = $this->paymentService->persistPayment(
                    $paymentType, $grandTotal, $this->parameters['member_id'],
                    $productstring, $this->parameters['productCount'],
                    json_encode($itemList), $txnid, $this
                    );

            if($return['o_success'] <= 0){
                $response['message'] = $return['o_message'];
            }
            else{
                $v_order_id = $return['v_order_id'];
                $invoice = $return['invoice_no'];
                $status = 's';

                foreach ($itemList as $key => $value) {
                    // stub functions
                    //$itemComplete = $this->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    //$this->updateSoldoutStatus($value['id']);
                }

                // stub functions
                //$this->removeItemFromCart();
                //$this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$v_order_id, 'invoice_no'=>$invoice));
                //$this->sendNotification();

                // Retrieve order
                $order = $this->em->getRepository('EasyShop\Entities\EsOrder')
                                        ->find($v_order_id);

                // payment method
                $paymentMethod = $this->em->getRepository('EasyShop\Entities\EsPaymentMethod')
                                        ->find(3);

                // update payment gateway DB here
                $paymentHistory = new EsPaymentGateway();
                $paymentHistory->setAmount($grandTotal);
                $paymentHistory->setDateAdded(date_create(date("Y-m-d H:i:s")));
                $paymentHistory->setOrder($order);
                $paymentHistory->setPaymentMethod($paymentMethod);
                $this->em->persist($paymentHistory);
                $this->em->flush();
            }
        }
        else{
            $response['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
        }
        $response['status'] = $status;
        $response['txnid'] = $txnid;
        return $response;
    }

    public function getExternalCharge()
    {
        return 0;
    }

    private function generateReferenceNumber($member_id)
    {
        return 'COD-'.date('ymdhs').'-'.$member_id;
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