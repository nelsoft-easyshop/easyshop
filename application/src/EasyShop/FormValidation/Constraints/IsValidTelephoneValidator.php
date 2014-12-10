<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidTelephoneValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if((!preg_match('/^[0-9]{3}-[0-9]{4}$/', $value))){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
