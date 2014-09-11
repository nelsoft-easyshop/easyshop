<?php

namespace EasyShop\PaymentGateways;

use Doctrine\ORM\Query\ResultSetMapping;
use EasyShop\Entities\EsAddress;
use EasyShop\Entities\EsOrderShippingAddress;
use EasyShop\Entities\EsLocationLookup;
use EasyShop\Entities\EsOrder;
use EasyShop\Entities\EsMember;
use EasyShop\Entities\EsPaymentMethod;
use EasyShop\Entities\EsOrderStatus;
use EasyShop\Entities\EsOrderHistory;
use EasyShop\Entities\EsOrderProduct;
use EasyShop\Entities\EsProduct;
use EasyShop\Entities\EsOrderProductStatus;
use EasyShop\Entities\EsOrderBillingInfo;
use EasyShop\Entities\EsBillingInfo;
use EasyShop\Entities\EsBankInfo;
use EasyShop\Entities\EsOrderProductAttr;
use EasyShop\Entities\EsOrderProductHistory;
use \DateTime;


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
    protected $paymentService

    /**
     * @var int
     */
    protected $PayMentPayPal = 1;


    /**
     * @var int
     */
    protected $PayMentDragonPay = 2;

    /**
     * @var int
     */
    protected $PayMentCashOnDelivery = 3;

    /**
     * @var int
     */
    protected $PayMentPesoPayCC = 4;

    /**
     * @var int
     */
    protected $PayMentDirectBankDeposit = 5;

    /**
     * Constructor
     * 
     */
    protected function __construct($params = [])
    {
        $this->em = $params['em'];
        $this->request = $params['request'];
        $this->pointTracker = $params['pointTracker'];
        $this->paymentService = $params['paymentService'];
        $this->setParameters($params);
        $this->paymentMethodName = $this->parameters['method'];
        $this->amountAllocated = $this->parameters['amount'];
    }

    /**
     * Abstract Methods
     */
    abstract public function pay();
    abstract public function getExternalCharge();
    abstract public function getOrderStatus();
    abstract public function getOrderProductStatus();
    abstract public function generateReferenceNumber();


    /**
     * Getters and Setters
     */
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
