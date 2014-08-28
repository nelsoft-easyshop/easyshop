<?php

namespace EasyShop\PaymentGateways;

abstract class AbstractGateway implements GatewayInterface
{
    // amount to be paid with this method gateway
    protected $amountAllocated;

    // payment method name
    protected $paymentMethodName;

    // parameters related to this payment gateway
    protected $parameters;

    // entity manager instance
    protected $em;

    protected function __construct($params = [])
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
        $this->setParameters($params);
        $this->paymentMethodName = $this->parameters['method'];
        $this->amountAllocated = $this->parameters['amount'];
    }

    // force child classes to implement this method
    abstract public function pay();

    // Getters and Setters

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