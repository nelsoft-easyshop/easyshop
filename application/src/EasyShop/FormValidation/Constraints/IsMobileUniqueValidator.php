<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsMobileUniqueValidator extends ConstraintValidator
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
        if($value[0] === '0' && strlen($value) === 11){
            $value = substr($value , 1);
        }
    
        $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['contactno' => $value]);
        if($user){   
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}

