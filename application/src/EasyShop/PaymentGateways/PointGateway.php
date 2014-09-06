<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPointHistory;
use EasyShop\Entities\EsOrderProduct;

/**
 * Point Gateway Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PointGateway extends AbstractGateway
{

    /**
     * Point Tracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;

    /**
     * Constructor
     * 
     */
    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->pointTracker = get_instance()->kernel->serviceContainer['point_tracker'];
    }

    /**
     * Pay method for Point Gateway Class
     * 
     */
    public function pay()
    {
        // get id of action
        $actionId = $this->pointTracker->getActionId($this->parameters['pointtype']);

        // update user points!
        $historyObj = $this->pointTracker->spendUserPoint(
            $this->parameters['member_id'],
            $actionId,
            $this->parameters['amount']
            );

        // if 'purchase', add items as JSON
        if($this->parameters['pointtype'] == 'purchase'){ 

            // order products
            $orderProducts = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                        ->findBy([
                            'order' => $this->parameters['order_id'],
                            'product' => array_keys($this->parameters['products'])
                            ]);

            $breakdown = [];
            $tempArray = $this->parameters['products'];
            foreach($orderProducts as $prods){
                $data['order_product_id'] = $prods->getIdOrderProduct();
                $data['points'] = $tempArray[$prods->getProduct()->getIdProduct()];
                unset($tempArray[$prods->getProduct()->getIdProduct()]);
                $breakdown[] = $data;
            }

            $jsonData = json_encode($breakdown);
            
            // update history data field
            $historyObj->setData($jsonData);
            $this->em->flush();
        }
    }
}

/*
    Params needed
        'name' => unique string for referencing this gateway,
        'method' => 'Point', 
        'amount' => amount allocated,
        'member_id' => id of member
        'pointtype' => type of point used
        'products' => ['prod_id' => point, 'prod_id' => point]
        'order_id' => order id of purchase 
*/

