<?php

namespace EasyShop\PaymentService;

/**
 * Payment Service Class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
class PaymentService
{
    /**
     * Gateway path
     *
     * @var string
     */
    private $gatewayPath = "EasyShop\PaymentGateways";
    
    /**
     * Gateway instances holder
     * ['name' => obj]
     *
     * @var mixed
     */
    private $gateways = [];

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Constructor
     * 
     */
    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $breakdown Parameters for each gateway
     */
    public function setBreakdown($breakdown)
    {
        foreach($breakdown as $breakdown){
            $path = $this->gatewayPath . "\\" . $breakdown["method"] . "Gateway";
            $obj = new $path($breakdown);
            $this->gateways[$breakdown["name"]] = $obj; 
        }
    }


    /**
     * Returns all gateways being handled
     *
     * @return mixed
     */
    public function getAllGateways()
    {
        return $this->gateways;
    }

    /**
     * Returns a certain gateway
     *
     * @param string $name Name of gateway to be retrieved
     *
     * @return EasyShop\PaymentGateways\AbstractGateway
     */
    // returns a certain gateway
    public function getOneGateway($name)
    {
        return $this->gateways[$name];
    }

    /**
     * Deletes a certain gateway
     *
     * @param string $name Name of gateway to be deleted
     */
    public function deleteGateway($name)
    {
        unset($this->gateways[$name]);
    }


    /**
     * Executes payment transaction for all registered gateways
     */
    public function pay()
    {
        foreach ($this->gateways as $gateway) {
            $gateway->pay();
        }
    }
}
