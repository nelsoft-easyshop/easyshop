<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsUsernameUniqueValidator extends ConstraintValidator
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
        $user = $this->em->getRepository('EasyShop\Entities\EsMember')
                                ->findOneBy(['username' => $value]);
        if($user){   
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}

