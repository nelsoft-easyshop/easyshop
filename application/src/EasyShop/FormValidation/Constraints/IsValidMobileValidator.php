<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidMobileValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if(!( $value == ''  || preg_match('/^(08|09)[0-9]{9}$/', $value) )){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
