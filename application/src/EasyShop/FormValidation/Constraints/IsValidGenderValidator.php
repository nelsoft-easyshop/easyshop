<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidGenderValidator extends ConstraintValidator
{
    /**
     * Allowed values for gender field
     *
     * @var array
     */
    private $gender = ["M","F"];
    public function validate($value, Constraint $constraint)
    {
        if(!in_array(strtoupper($value), $this->gender)){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
