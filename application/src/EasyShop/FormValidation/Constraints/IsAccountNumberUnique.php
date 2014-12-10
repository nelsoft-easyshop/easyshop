<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsAccountNumberUnique extends Constraint
{
    public $message = 'The account number "%string%" is already in use';
    
    /**
     * Member ID
     *
     * @var integer
     */
    private $memberId;
    
    /**
     * BillingInfo ID
     *
     * @var integer
     */
    private $accountId;

    /**
     * Constraint constructor
     *
     * @param integer $memberId
     */
    public function __construct($options)
    {
        $this->accountId = null;
        if(isset($options['accountId'])){
            $this->accountId = $options['accountId'];
        }        
        if(is_int($options['memberId'])){
            $this->memberId = $options['memberId'];
        }
        else{
            throw new \Exception("The member ID must be an integer");
        }
    }

    
    /**
     * Returns the memberId
     *
     * @return integer
     */
    public function getMemberId()
    {
        return $this->memberId;
    }
    
    /**
     * Returns the accountId
     *
     * @return integer
     */
    public function getAccountId()
    {
        return $this->accountId;
    }
    
}
