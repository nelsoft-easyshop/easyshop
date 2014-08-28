<?php

namespace EasyShop\PaymentService;

class PaymentService
{
    // path to gateway
    private $gatewayPath = "EasyShop\PaymentGateways";
    
    // holds instantiated gateways
    // name => object instance
    private $gateways = [];

    // holds entity manager for database actions
    private $em;

    // constructor
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    // instantiate all gateways here
    public function setBreakdown($breakdown)
    {
        foreach($breakdown as $breakdown){
            $path = $this->gatewayPath . "\\" . $breakdown["method"] . "Gateway";
            $obj = new $path($breakdown);
            $this->gateways[$breakdown["name"]] = $obj; 
        }
    }

    // returns all gateways being handled
    public function getAllGateways()
    {
        return $this->gateways;
    }

    // returns a certain gateway
    public function getOneGateway($name)
    {
        return $this->gateways[$name];
    }

    // deletes a certain gateway
    public function deleteGateway($name)
    {
        unset($this->gateways[$name]);
    }

    // executes payment transaction for all
    // registered gateways
    public function pay()
    {
        foreach ($this->gateways as $gateway) {
            $gateway->pay();
        }
    }
}