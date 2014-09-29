<?php

namespace EasyShop\PaymentGateways;


/**
 * Base payment gateway class
 *
 * @author LA roberto <la.roberto@easyshop.ph>
 */
abstract class AbstractGateway implements GatewayInterface
{
    /**
     * @var mixed
     */
    protected $parameters;

    /**
     * Entity Manager instance
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     * Http foundation Request instance
     *
     * @var Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * PointTracker instance
     *
     * @var EasyShop\PointTracker\PointTracker
     */
    protected $pointTracker;

    /**
     * Payment Service instance
     *
     * @var EasyShop\PaymentService\PaymentService
     */
    protected $paymentService;

    /**
     * Constructor
     * 
     */
    protected function __construct($em, $request, $pointTracker, $paymentService, $params=[])
    {
        $this->em = $em;
        $this->request = $request;
        $this->pointTracker = $pointTracker;
        $this->paymentService = $paymentService;
        $this->setParameters($params);
    }

    /**
     * Abstract Methods
     */

    abstract public function pay();
    abstract public function getExternalCharge();
    abstract public function getOrderStatus();
    abstract public function getOrderProductStatus();
    abstract public function generateReferenceNumber($memberId);
    
    /**
     * Getters and Setters
     */
    
    public function setParameters($param = [])
    {
        foreach ($param as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    public function setParameter($key, $value)
    {
         $this->parameters[$key] = $value;
    }

    public function getParameter($key)
    {
        return $this->parameters[$key];
    }
}
