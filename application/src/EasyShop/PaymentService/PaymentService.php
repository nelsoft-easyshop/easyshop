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
     * ['name' => ['object' => obj, 'return_val' => return]]
     *
     * @var mixed
     */
    private $gateways = [];

    /**
     * Gateway return value holder
     * ['name' => return_val]
     *
     * @var mixed
     */
    private $returnValues = [];

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Point Tracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;

    /**
     * Http-foundation Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * Constructor
     * 
     */
    public function __construct($em, $request, $pointTracker)
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
    }


    /**
     * Instantiate gateways
     *
     * @param mixed $breakdown Parameters for each gateway
     */
    public function setBreakdown($breakdown)
    {
        foreach($breakdown as $breakdown){
            $breakdown['em'] = $this->em;
            $breakdown['request'] = $this->request;
            $breakdown['pointTracker'] = $this->pointTracker;
            $path = $this->gatewayPath . "\\" . $breakdown["method"] . "Gateway";
            $obj = new $path($breakdown);
            $this->gateways[$breakdown["name"]] = $obj; 
            $this->returnValues[$breakdown["name"]] = NULL;
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
     * Retrieves a return value from a certain gateway
     *
     * @param string $name Name of gateway to be deleted
     *
     * @return mixed
     */
    public function getReturnValue($name)
    {
        return $this->returnValues[$name];
    }

    /**
     * Executes payment transaction for all registered gateways
     */
    public function pay()
    {
        foreach ($this->gateways as $gateway) {
            $this->returnValues[array_search($gateway, $this->gateways)] = $gateway->pay();
        }
    }

}

