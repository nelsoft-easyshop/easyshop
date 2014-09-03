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

    /**
     * Gateway return value holder
     * ['name' => returnval]
     *
     * @var mixed
     */
    private $returnValue = [];

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Http foundation Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * PointTracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    private $pointTracker;


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
        $services['em'] =  $this->em;
        $services['request'] =  $this->request;
        $services['pointTracker'] =  $this->pointTracker;
        foreach($breakdown as $breakdown){
            $path = $this->gatewayPath . "\\" . $breakdown["method"] . "Gateway";
            $obj = new $path(array_merge($breakdown,$services));
            $this->gateways[$breakdown["name"]] = $obj; 
            $this->returnValue[$breakdown["name"]] = NULL;
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

    public function getReturnValue($name)
    {
        return $this->returnValue[$name];
    }

    /**
     * Executes payment transaction for all registered gateways
     */
    public function pay()
    {
        foreach ($this->gateways as $gateway) {
            $this->returnValue[array_search($gateway, $this->gateways)] = $gateway->pay();
        }
    }

}

