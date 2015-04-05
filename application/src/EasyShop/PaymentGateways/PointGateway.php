<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPointHistory;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsPaymentMethod as EsPaymentMethod;
use EasyShop\Entities\EsPointType as EsPointType;

/**
 * Point Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PointGateway extends AbstractGateway
{
    const MAX_POINT_ALLOWED = PHP_INT_MAX;

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
     * Pay method for Point Gateway Class
     * 
     */
    public function pay($param1 = null, $param2 = null, $param3 = null)
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

    // Dummy functions to adhere to abstract gateway
    public function getExternalCharge(){}
    public function getOrderStatus(){}
    public function getOrderProductStatus(){}
    public function generateReferenceNumber($memberId){}
}

/*
    Params needed
        method:"Point", 
        amount:amount allocated,
        pointtype:type of point used
*/

