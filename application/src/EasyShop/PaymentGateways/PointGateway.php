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
        $history_obj = $this->pointTracker->spendUserPoint(
            $this->parameters['member_id'],
            $actionId,
            $this->parameters['amount']
            );

        // if 'purchase', add items as JSON
        if($this->parameters['pointtype'] == 'purchase'){ 

            // order products
            $order_products = $this->em->getRepository('EasyShop\Entities\EsOrderProduct')
                        ->findBy([
                            'order' => $this->parameters['order_id'],
                            'product' => array_keys($this->parameters['products'])
                            ]);

            $breakdown = [];
            $temp_arr = $this->parameters['products'];
            foreach($order_products as $prods){
                $data['order_product_id'] = $prods->getIdOrderProduct();
                $data['points'] = $temp_arr[$prods->getProduct()->getIdProduct()];
                unset($temp_arr[$prods->getProduct()->getIdProduct()]);
                $breakdown[] = $data;
            }

            $json_data = json_encode($breakdown);
            
            // update history data field
            $history_obj->setData($json_data);
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