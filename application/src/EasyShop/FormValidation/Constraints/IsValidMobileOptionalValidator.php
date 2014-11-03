<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidMobileOptionalValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(strlen($value) >= 1 && !preg_match('/^(08|09)[0-9]{9}$/', $value)){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
