<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsAccountNumberUniqueValidator extends ConstraintValidator
{
    /**
     * The entity manager
     *
     */
    private $em;

    public function __construct()
    {
        $this->em = get_instance()->kernel->serviceContainer['entity_manager'];
    }

    public function validate($value, Constraint $constraint)
    {
        $memberId = $constraint->getMemberId();
        $paymentAccount = $this->em->getRepository('EasyShop\Entities\EsBillingInfo')
                                   ->findOneBy([
                                        'member' => $memberId, 
                                        'bankAccountNumber' => $value,
                                    ]);
        if($paymentAccount){   
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
