<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidPasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if( !( (preg_match('/[a-zA-Z]/', $value)) && (preg_match('/\d/',$value)) && !(preg_match('/\s/',$value)) ) )
        {
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
