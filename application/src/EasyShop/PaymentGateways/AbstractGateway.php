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
     * @var int
     */
    protected $amountAllocated;

    /**
     * @var string
     */
    protected $paymentMethodName;

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
     * Constructor
     * 
     */
    protected function __construct($params = [])
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        $this->setParameters($params);
        $this->paymentMethodName = $this->parameters['method'];
        $this->amountAllocated = $this->parameters['amount'];
    }

    /**
     * Pay Method
     */
    abstract public function pay();

    public function getPaymentMethodName()
    {
        return $this->paymentMethodName;
    }

    public function getAmountAllocated()
    {
        return $this->amountAllocated;
    }

    public function setAmountAllocated($newAmount)
    {
        $this->amountAllocated = $newAmount;
    }

    public function setParameters($param = [])
    {
        foreach ($param as $key => $value) {
            $this->parameters[$key] = $value;
        }
    }

    public function getParameter($key)
    {
        return $this->parameters[$key];
    }
}
