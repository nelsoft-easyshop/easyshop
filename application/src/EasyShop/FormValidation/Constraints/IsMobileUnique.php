<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsMobileUnique extends Constraint
{
    public $message = 'The mobile "0%string%" is already in use';

    /**
     * Member ID
     *
     * @var integer
     */
    private $memberId = 0;

    /**
     * Constraint constructor
     *
     * @param integer $memberId
     */
    public function __construct($options = [])
    {      
        if(isset($options["memberId"])){
            if( is_int($options['memberId'])){
                $this->memberId = $options['memberId'];
            }
            else{
                throw new \Exception("The member ID must be an integer");
            }
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
}

