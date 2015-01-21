<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidStoreNameOptionalValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if((strlen($value) > 0 && strlen($value) < 5) || strlen($value) > 60){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
