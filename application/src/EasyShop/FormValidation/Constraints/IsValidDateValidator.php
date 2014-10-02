<?php

namespace EasyShop\FormValidation\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsValidDateValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $comp = preg_split('/[-\/]+/', $date);
        $year = intval($comp[0]);
        $month = intval($comp[1]);
        $day = intval($comp[2]);

        if( !(trim($value) === "" || checkdate($month, $day, $year)) ){
            $this->context->addViolation(
                $constraint->message,
                array('%string%' => $value)
            );
        }
    }
}
