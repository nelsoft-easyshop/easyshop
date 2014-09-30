<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidAddressOptionalValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if((strlen($value) > 0 && strlen($value) < 4) || strlen($value) > 250){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
