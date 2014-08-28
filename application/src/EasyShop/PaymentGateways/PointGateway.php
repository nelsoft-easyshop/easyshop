<?php

namespace EasyShop\PaymentGateways;

use EasyShop\Entities\EsPointHistory;
use EasyShop\Entities\EsOrderProduct;

class PointGateway extends AbstractGateway
{

    private $pointTracker;

    public function __construct($params = [])
    {
        parent::__construct($params);
        $this->pointTracker = get_instance()->kernel->serviceContainer['point_tracker'];
        echo "<pre>"; print_r($this->parameters); echo "</pre>";
    }

    public function pay()
    {
        // get id of action
        $actionId = $this->pointTracker->getActionId($this->parameters['pointtype']);

        // update user points!
        $history_id = $this->pointTracker->spendUserPoint(
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
            
            // history object
            $history = $this->em->getRepository('EasyShop\Entities\EsPointHistory')
                        ->find($history_id);

            // update history data field
            $history->setData($json_data);
            $this->em->flush();
        }
    }
}