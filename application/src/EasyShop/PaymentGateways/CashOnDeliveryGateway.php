<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPaymentGateway;

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
        $txnid = $this->generateReferenceNumber($paymentType, $this->parameters['member_id']);
        
        //if($this->parameters['qtysuccess'] == $this->parameters['productCount']){
        if(true){
            $return = $this->persistPayment(
                    $paymentType, $grandTotal, $this->parameters['member_id'],
                    $productstring, $this->parameters['productCount'],
                    json_encode($itemList), $txnid
                    );

            if($return['o_success'] <= 0){
                $response['message'] = $return['o_message'];
            }
            else{

                $v_order_id = $return['v_order_id'];
                $invoice = $return['invoice_no'];
                $status = 's';

                foreach ($itemList as $key => $value) {
                    // do something
                    //$itemComplete = $this->deductQuantity($value['id'],$value['product_itemID'],$value['qty']);
                    //$this->updateSoldoutStatus($value['id']);
                }

                // stub functions
                //$this->removeItemFromCart();
                //$this->sendNotification(array('member_id'=>$member_id, 'order_id'=>$v_order_id, 'invoice_no'=>$invoice));
                //$this->sendNotification();

                // update payment gateway DB here
                $paymentHistory = new EsPaymentGateway();
                $paymentHistory->

                //$this->em->getRepository('EasyShop\Entities\EsPaymentGateway');
            }
        }
        else{
            $response['message'] = 'The availability of one of your items is below your desired quantity. Someone may have purchased the item before you completed your payment.';
        }
        $response['status'] = $status;
        $response['txnid'] = $txnid;
        return $response;
    }

}